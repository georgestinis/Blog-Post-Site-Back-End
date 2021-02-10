<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostFormRequest;
use App\Http\Models\Post;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        //fetch 5 posts from database which are active and latest
        $posts = Posts::where('active', 1)->orderBy('created_at', 'desc')->paginate(5);
        $title = 'Latest Posts';
        return view('home')->withPosts($posts)->withTitle($title);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->can_post()) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFormRequest $request)
    {
        $data = $request->all();
        $post = new Post();
        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->slug = Str::slug($post->title);

        $duplicate = Post::where('slug', $post->slug)->first();
        if ($duplicate) {
            return redirect('new-post')->withErrors('Title already exists.')->withInput();
        }

        $post->author_id = $request->user()->id;
        if ($request->has('save')) {
            $post->active = 0;
            $message = 'Post saved successfully';
        }
        else {
            $post->active = 1;
            $message = 'Post published successfully';
        }

        $post->save();
        return redirect('edit/' . $post->slug)->withMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!$post) {
            return redirect('/')->withErrors('requested page not found');
        }

        $comments = $post->comments;
        return view('post.show')->withPost($post)->withComments($comments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json(["error" => "Record not found"], 404);
        }

        $post->update($request->all());

        return response()->json($post, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json(["error" => "Record not found"], 404);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}

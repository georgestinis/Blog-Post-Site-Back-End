<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = ['codechief.org', 'wordpress.org', 'laramust.com'];

        foreach ($posts as $key => $value) {
        	Post::create(['body'=>$value]);
        }

        $post = Post::find(4);
        $response = auth()->user()->toggleLike($post);
        return response()->json($post->likers()->get(), 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function posts(){
        $posts = Post::get();
        return view('posts', compact('posts'));
    }

    public function LikePost(Request $request){
        $post = Post::find($request->id);
        $response = auth()->user()->toggleLike($post);

        return response()->json(['success'=>$response]);
    }
}

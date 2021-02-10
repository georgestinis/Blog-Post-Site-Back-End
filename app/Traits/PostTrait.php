<?php

namespace App\Traits;
use App\Http\Models\Post;

trait PostTrait {
    public function updatePost($request, $post) {
        $title = $request->input('title');
        $slug = Str::slug($title);
        
        $duplicate = Post::where('slug', $slug)->first();
        if ($duplicate) {
            if ($duplicate->id != $post->id) {
                return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
            }
            else {
                $post->slug = $slug;
            }
        }

        $post->title = $title;
        $post->body = $request->input('body');

        $array;

        if ($request->has('save')) {
            $post->active = 0;
            $array = array("message" => 'Post saved successfully', "landing" => 'edit/' . $post->slug);
        }
        else {
            $post->active = 1;
            $array = array("message" => 'Post updated successfully', "landing" => $post->slug);
        }

        $post->save();

        return $array;
        
    }
}
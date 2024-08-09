<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    //
    public function viewPost(Post $page){
        // $page['body'] = strip_tags(Str::markdown($page->body),'<p><h1><ul>');
        // if($page->user_id === auth()->user()->id){
        //     return 'you are the author';
        // }
        // return 'you are not the author';

        $page['body'] = Str::markdown($page->body);
        return view("single-post", ["page"=> $page]);

    }
    public function showCreateForm(){
        // Middleware
        // if(!auth()->check()){
        //     return redirect("");
        // }
        return view("create-post");
    }
    public function saveCreateForm(Request $request){
        $incomingFileds = $request->validate([
            "title"=> "required",
            "body"=> "required",
        ]);
        $incomingFileds['title'] = strip_tags($incomingFileds['title']);
        $incomingFileds['body'] = strip_tags($incomingFileds['body']);
        $incomingFileds['user_id'] = auth()->user()->id;

        $newPost = Post::create($incomingFileds);
        return redirect("/post/{$newPost->id}")->with('success','New Post successfully created!');
    }
    public function delete(Post $page)
    {
        // if(auth()->user()->cannot('delete', $page)) {
        //     return "you cannot do that!";
        // }
        $this->authorize('delete', $page);
        Log::info('Authorization check passed for delete', [
            'user_id' => auth()->user()->id,
            'post_id' => $page->user_id
        ]);
        $page->delete();

        return redirect("/profile/" . auth()->user()->username)->with("success", "Post successfully deleted!");
    }

    public function editPostForm(Post $page){
        // $post = Post::find($page->id);
        return view("edit-post", ["page"=> $page]);
    }

    public function updatePost(Request $request, Post $page){
        $incomingFields = $request->validate([
            "title"=> "required",
            "body"=> "required"
        ]);
        $incomingFields["title"] = strip_tags($incomingFields["title"]);
        $incomingFields["body"] = strip_tags($incomingFields["body"]);

        $page->update($incomingFields);
        return back()->with("success","Post successfully updated!");
    }

    public function search($term){
        $posts = Post::search($term)->get();
        $posts->load("user:id,username,avatar");
        return $posts;

    }
}

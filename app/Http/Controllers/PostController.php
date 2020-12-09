<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store','destroy']);
    }
    public function index()
    {
        $posts = POST::with(['user','likes'])->latest()->paginate(20);
        // dd($posts);
        return view('posts.index',compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'body'=>'required'
        ]);

        // Post::create([
        //     'user_id'=>auth()->user()->id,
        //     'body'=>$request->body
        // ]);

        $request->user()->posts()->create($request->only('body'));

        return back();
    }

    public function destroy(Post $post) 
    {
        $this->authorize('delete', $post);
        $post->delete();

        return back();
    }
}

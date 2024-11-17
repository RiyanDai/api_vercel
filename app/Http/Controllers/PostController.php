<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    //get all posts
    public function index()
    {
        // Ambil semua post dengan informasi user, komentar, dan likes
        $posts = Post::orderBy('created_at', 'desc')
        ->with('user:id,name,image')  // Informasi user yang membuat post
        ->withCount('comments', 'likes')  // Hitung jumlah komentar dan likes
        ->with(['likes' => function ($query) {
            $query->select('id', 'user_id', 'post_id'); // Hanya ambil kolom tertentu dari likes
        }])
        ->get();

        return response([
            'posts' => $posts
        ], 200);
    }


    //get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    //create post
     // Create post
     public function store(Request $request)
     {
          $attrs = $request->validate([
            'body' => 'required|string'
          ]);

          $image = $this->saveImage($request->image, 'posts');

          $post = Post::create([
            'body' => $attrs['body'],
            'user_id'=>auth()->user()->id,
            'image' => $image
          ]);

          return response([
            'message' => 'Post created.',
            'post' => $post
          ], 200);
     }


      // Update post
      public function update(Request $request, $id)
      {     
            $post = Post::find($id);
            if (!$post) {
                return response([
                    'message' => 'Post not found.'
                ], 403);
            }

            if ($post->user_id !=auth()->user()->id) {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }
            
            //validate fields
           $attrs = $request->validate([
             'body' => 'required|string'
           ]);
           
           $post->update([
            'body'=>$attrs['body']
           ]); 
           
           return response([
             'message' => 'Post updated.',
             'post' => $post
           ], 200);
    }

    //delete

    public function destroy($id)
    {
        $post = Post::find($id);

        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if ($post->user_id !=auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.',
          ], 200);
    }

}

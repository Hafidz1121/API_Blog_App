<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function myPosts() {
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'user' => $user
        ]);
    }

    public function posts() {
        $posts = Post::orderBy('id', 'desc')->get();

        foreach ($posts as $post) {
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;

            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function create(Request $request) {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;

        // Check available photo
        if ($request->photo != '') {
            // Choose a unique name for photo
            $photo = time().'.jpg';
            // Link storage to folder public (run this 'php artisan storage:link')
            file_put_contents('storage/posts/'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
    }

    public function update(Request $request) {
        $post = Post::find($request->id);

        // Check is editing his own post
        // And Check user id with post user id
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        } 

        $post->desc = $request->desc;
        $post->update();

        return response()->json([
            'success' => true,
            'message' => 'post edited'
        ]);
    }

    public function delete(Request $request) {
        $post = Post::find($request->id);

        // Check is deleting his own post
        // And Check user id with post user id
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ]);
        } 

        // Check id post has photo to delete
        if ($post->photo != '') {
            Storage::delete('public/posts/'.$post->photo);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }
}

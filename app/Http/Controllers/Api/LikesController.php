<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Like;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function like(Request $request) {
        $like = Like::where('post_id', $request->id)->where('user_id', Auth::user()->id)->get();

        // Check if it return 0 make the post unliked and should be liked
        if (count($like) > 0) {
            // Handle cant have like by my self more then one
            $like[0]->delete();

            return response()->json([
                'success' => true,
                'message' => 'unliked'
            ]);
        }

        $like = new Like;
        $like->user_id = Auth::user()->id;
        $like->post_id = $request->id;
        $like->save();

        return response()->json([
            'success' => true,
            'message' => 'liked'
        ]);
    }
}

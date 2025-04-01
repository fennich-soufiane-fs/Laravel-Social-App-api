<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeCommentController extends Controller
{
    public function PostComment(Request $request) {
        $request->validate([
            'post_id' => 'required',
            'content' => 'required|min:1|max:250'
        ]);
        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
        return response()->json($comment);
    }

    public function LikeUnlike (Request $request, $postId) {
        $user = $request->user();
        $exist = Like::where('post_id', $postId)->where('user_id', $user->id)->first();
        if($exist) {
            $exist->delete();
            return response()->json([
                'message' => 'Like removed successfully.',
                'liked' => false
            ]);
        } else {
            $like = Like::create([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
            return response()->json([
                'message' => 'Post liked successfully.',
                'liked' => true
            ]);
        }
    }
}

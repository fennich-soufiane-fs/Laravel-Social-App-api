<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $posts = Post::with(['user:id', 'comments.user:id,first_name,last_name', 'likes.user:id,first_name,last_name'])
                            ->withCount('likes','comments')
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->cursorpaginate();
        return response()->json($posts);
    }

    public function publicPosts()
    {
        $posts = Post::where('visibility', 'public')
                        ->orderBy('created_at', 'desc')
                        ->cursorpaginate();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $path = $image->store('post_image');
        } else {
            $request->validate([
                'text' => 'required|min:5',
            ]);
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'text' => $request->text ?? null,
            'image' => $path ?? null,
            'visibility' => $request->visibility ?? 'public',
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'visibility' => 'in:public,private',
        ]);
        if(($request->text != null || $request->text != '') && $request->text != $post->text) {
            $request->validate([
                'text' => 'required'
            ]);
        }

        $post->update($request->only(['text', 'visibility']));
        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}

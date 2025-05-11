<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return back();
    }

    public function destroy(Comment $comment)
    {
        // Only admin or comment owner can delete
        if (auth()->id() !== $comment->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $comment->delete();

        return back();
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;
use App\Notifications\GeneralNotification;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:1000']
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);

        // Notify post owner (not self)
        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new GeneralNotification(
                'comment',
                $request->user()->name . ' commented on your post: "' . \Str::limit($request->body, 50) . '"',
                route('posts.show', $post),
                $request->user()->id,
                $request->user()->name
            ));
        }

        return back();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $post = $comment->post;
        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}

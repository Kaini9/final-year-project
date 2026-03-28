<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'], // 5MB max
            'caption' => ['nullable', 'string', 'max:2200'],
        ]);

        $post = new Post([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'image' => $request->file('image')->store('posts', 'public'),
        ]);

        $post->save();

        return back()->with('status', 'Post published successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();

        return back()->with('status', 'Post deleted.');
    }
}

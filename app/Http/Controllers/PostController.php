<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function api(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = 4;

        // Get paginated posts with pagination info
        $posts = Post::with(['user.profile', 'user.role', 'likes', 'comments.user'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $posts->items(),
            'has_more' => $posts->hasMorePages(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:3'],
            'images.*' => ['required', 'image', 'max:5120'], // 5MB max per image
            'caption' => ['nullable', 'string', 'max:2200'],
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts', 'public');
            }
        }

        $post = new Post([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'images' => $imagePaths, // Store as array
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

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}

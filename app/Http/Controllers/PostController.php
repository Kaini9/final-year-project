<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\CloudinaryService;

class PostController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

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
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['nullable', 'image', 'max:5120'], // 5MB max per image
            'caption' => ['nullable', 'string', 'max:2200'],
        ]);

        // Ensure at least caption or images are provided
        if (empty($request->caption) && !$request->hasFile('images')) {
            return back()->withErrors(['error' => 'Please provide a caption or upload images.']);
        }

        $imagePaths = [];
        $publicIds = [];

        if ($request->hasFile('images')) {
            $uploadedFiles = $this->cloudinary->uploadMultiple($request->file('images'));
            
            foreach ($uploadedFiles as $uploadedFile) {
                $imagePaths[] = $uploadedFile['url'];
                $publicIds[] = $uploadedFile['public_id'];
            }
        }

        $post = new Post([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'image_public_ids' => !empty($publicIds) ? $publicIds : null,
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

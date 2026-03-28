<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\JobApplication;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user.profile', 'user.role', 'likes', 'comments.user'])
                     ->latest()
                     ->paginate(20);

        $myApplications = JobApplication::with(['job.user.profile'])
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard', compact('posts', 'myApplications'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $type = $request->input('type', 'all');

        $users = collect();
        $jobs = collect();
        $posts = collect();

        if (empty($q)) {
            return view('search.index', compact('q', 'type', 'users', 'jobs', 'posts'));
        }

        if ($type === 'users' || $type === 'all') {
            $userQuery = User::with('profile', 'role', 'verification')
                ->where('name', 'like', "%{$q}%")
                ->orWhereHas('role', function($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                ->latest();
            
            $users = $type === 'all' ? $userQuery->take(6)->get() : $userQuery->paginate(12)->appends($request->query());
        }

        if ($type === 'jobs' || $type === 'all') {
            $jobQuery = Job::with('user.profile', 'user.verification')
                ->where('status', 'active')
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('role_required', 'like', "%{$q}%");
                })
                ->latest();
            
            $jobs = $type === 'all' ? $jobQuery->take(5)->get() : $jobQuery->paginate(10)->appends($request->query());
        }

        if ($type === 'posts' || $type === 'all') {
            $postQuery = Post::with('user.profile', 'user.verification', 'likes', 'comments')
                ->where('caption', 'like', "%{$q}%")
                ->latest();
            
            $posts = $type === 'all' ? $postQuery->take(10)->get() : $postQuery->paginate(15)->appends($request->query());
        }

        return view('search.index', compact('q', 'type', 'users', 'jobs', 'posts'));
    }
}

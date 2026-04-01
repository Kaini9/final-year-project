<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Verification;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminCreatedUserMail;
use App\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_jobs' => Job::count(),
            'active_jobs' => Job::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'pending_verifications' => Verification::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('metrics'));
    }

    public function users()
    {
        // Load users with their roles, latest first
        $users = User::with('role', 'profile')->latest()->paginate(20);
        $roles = Role::where('name', '!=', 'Admin')->get();
        return view('admin.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => $request->role_id,
            'email_verified_at' => now(), // Auto verify
        ]);

        // Create empty profile
        $user->profile()->create([]);

        // Send Email
        Mail::to($user->email)->send(new AdminCreatedUserMail($user, $password));

        return back()->with('status', "User {$user->name} created and credentials have been emailed to them.");
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    public function verifications()
    {
        $verifications = Verification::with('user.profile')->latest()->paginate(20);
        return view('admin.verifications', compact('verifications'));
    }

    public function approveVerification(Verification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        $verification->user->notify(new GeneralNotification(
            'application_status',
            'Congratulations! Your account verification request has been approved and you have been awarded the blue tick.',
            route('profile.show', $verification->user->id),
            auth()->id(),
            'FashionConnect Admin'
        ));

        return back()->with('status', 'Verification approved.');
    }

    public function rejectVerification(Verification $verification)
    {
        $verification->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);

        $verification->user->notify(new GeneralNotification(
            'application_status',
            'Your verification request was declined. Please ensure your links are accurate and try again.',
            route('profile.settings'),
            auth()->id(),
            'FashionConnect Admin'
        ));

        return back()->with('status', 'Verification rejected.');
    }
}

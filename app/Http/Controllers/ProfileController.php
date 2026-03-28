<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Display the public profile.
     */
    public function show(User $user): View
    {
        $user->load(['profile', 'role']);
        
        $posts = collect();
        $jobs = collect();
        
        if ($user->hasRole('Designer')) {
            $jobs = $user->jobs()->latest()->get();
        } else {
            $posts = $user->posts()->latest()->get();
        }

        $isFollowing = \Illuminate\Support\Facades\Auth::user()->following()->where('users.id', $user->id)->exists();

        return view('profile.show', compact('user', 'posts', 'jobs', 'isFollowing'));
    }

    /**
     * Update the user's professional portfolio information.
     */
    public function updatePortfolio(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:500'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_website' => ['nullable', 'url', 'max:255'],
        ]);

        $user = $request->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $profile->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $profile->bio = $request->bio;
        $profile->location = $request->location;
        
        $skillsArray = [];
        if ($request->skills) {
            $skillsArray = array_filter(array_map('trim', explode(',', $request->skills)));
        }
        $profile->skills = collect($skillsArray)->values()->toArray();

        $socials = [];
        if ($request->social_instagram) $socials['instagram'] = $request->social_instagram;
        if ($request->social_linkedin) $socials['linkedin'] = $request->social_linkedin;
        if ($request->social_website) $socials['website'] = $request->social_website;
        $profile->social_links = collect($socials)->toArray();

        $profile->save();

        return Redirect::route('profile.edit')->with('status', 'portfolio-updated');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's account settings form.
     */
    public function settings(Request $request): View
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

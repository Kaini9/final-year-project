<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back();
        }

        $userId = $request->user()->id;
        
        if ($user->followers()->wherePivot('follower_id', $userId)->exists()) {
            $user->followers()->detach($userId);
        } else {
            $user->followers()->attach($userId);

            // Notify the user being followed
            $user->notify(new GeneralNotification(
                'follow',
                $request->user()->name . ' started following you.',
                route('profile.show', $request->user()),
                $request->user()->id,
                $request->user()->name
            ));
        }

        return back();
    }
}

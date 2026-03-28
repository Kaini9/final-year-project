<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Notifications\GeneralNotification;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $like = $post->likes()->where('user_id', $request->user()->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create([
                'user_id' => $request->user()->id
            ]);

            // Notify post owner (not self)
            if ($post->user_id !== $request->user()->id) {
                $post->user->notify(new GeneralNotification(
                    'like',
                    $request->user()->name . ' liked your post.',
                    route('dashboard'),
                    $request->user()->id,
                    $request->user()->name
                ));
            }
        }

        return back();
    }
}

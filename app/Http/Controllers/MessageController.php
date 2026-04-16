<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;

class MessageController extends Controller
{
    /**
     * Check if two users follow each other (mutual follow)
     */
    private function areFollowingEachOther($userId1, $userId2)
    {
        $user1FollowsUser2 = Auth::user()->following()
            ->where('following_id', $userId2)
            ->exists();

        $user2FollowsUser1 = User::find($userId2)->followers()
            ->where('follower_id', $userId1)
            ->exists();

        return $user1FollowsUser2 && $user2FollowsUser1;
    }

    /**
     * Build the conversations list for the sidebar.
     */
    private function getConversations()
    {
        $userId = Auth::id();

        $allMessages = Message::with(['sender.profile', 'receiver.profile', 'sender.role', 'receiver.role'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get();

        $conversations = collect();
        $processedUserIds = [];

        foreach ($allMessages as $msg) {
            /** @var \App\Models\Message $msg */
            $partnerId = $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;

            if (!in_array($partnerId, $processedUserIds)) {
                $processedUserIds[] = $partnerId;
                $partner = $msg->sender_id === $userId ? $msg->receiver : $msg->sender;

                $conversations->push((object)[
                    'partner' => $partner,
                    'latest_message' => $msg,
                    'is_spam' => $msg->is_spam,
                    'unread_count' => Message::where('sender_id', $partnerId)
                                            ->where('receiver_id', $userId)
                                            ->whereNull('read_at')
                                            ->count()
                ]);
            }
        }

        return $conversations;
    }

    /**
     * Display the inbox (no active chat selected).
     */
    public function index()
    {
        $conversations = $this->getConversations();
        
        // Separate conversations into regular and spam
        $regularConversations = $conversations->filter(fn($c) => !$c->is_spam);
        $spamConversations = $conversations->filter(fn($c) => $c->is_spam);
        
        $activeUser = null;
        $messages = collect();

        return view('messages.index', compact('regularConversations', 'spamConversations', 'activeUser', 'messages'));
    }

    /**
     * Display a specific chat thread with another user.
     */
    public function show(User $user)
    {
        $authId = Auth::id();

        if ($authId === $user->id) {
            return redirect()->route('messages.index')->with('error', 'You cannot message yourself.');
        }

        // Mark incoming unread messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Load thread chronologically
        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->oldest()
            ->get();

        $conversations = $this->getConversations();
        
        // Separate conversations into regular and spam
        $regularConversations = $conversations->filter(fn($c) => !$c->is_spam);
        $spamConversations = $conversations->filter(fn($c) => $c->is_spam);
        
        $activeUser = $user;

        return view('messages.index', compact('regularConversations', 'spamConversations', 'activeUser', 'messages'));
    }

    /**
     * Send a new message to a specific user.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'body' => 'required|string|max:1500'
        ]);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot message yourself.');
        }

        // Check if this is the first message between them
        $hasHistory = Message::where(function($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->exists();

        // Check if sender and receiver follow each other
        $isSpam = !$this->areFollowingEachOther(Auth::id(), $user->id);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'body' => $request->body,
            'is_spam' => $isSpam,
        ]);

        // Notify receiver if it's a new conversation
        if (!$hasHistory) {
            $notificationType = $isSpam ? 'message_request' : 'message';
            $user->notify(new GeneralNotification(
                $notificationType,
                Auth::user()->name . ' sent you a new message.',
                route('messages.index'),
                Auth::id(),
                Auth::user()->name
            ));
        }

        return redirect()->route('messages.show', $user);
    }

    /**
     * Move a conversation out of spam (accept message request).
     */
    public function acceptSpam(User $user)
    {
        $authId = Auth::id();

        // Update all messages in this conversation from spam
        Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->update(['is_spam' => false]);

        return redirect()->route('messages.show', $user)
            ->with('status', 'Message request accepted! This conversation moved to your inbox.');
    }
}

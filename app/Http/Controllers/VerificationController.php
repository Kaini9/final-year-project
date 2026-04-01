<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'social_link' => 'required|url|max:1000',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Check if an active or pending verification already exists
        $existing = $user->verification;

        if ($existing && in_array($existing->status, ['pending', 'approved'])) {
            return back()->with('error', 'You already have a pending or approved verification request.');
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('verifications', 'public');
        }

        if ($existing) {
            $existing->update([
                'status' => 'pending',
                'social_link' => $request->social_link,
                'document_path' => $documentPath ?: $existing->document_path,
            ]);
        } else {
            $verification = Verification::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'social_link' => $request->social_link,
                'document_path' => $documentPath,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]
        );

        // Redirect directly to the Khalti payment gateway
        return redirect()->route('khalti.initiate', $verification);}
    }
}

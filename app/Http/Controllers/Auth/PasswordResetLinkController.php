<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Services\OtpService;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Always put the email in session so the next screen knows who it is
        $request->session()->put('reset_email', $request->email);

        if ($user && $user->is_active) {
            try {
                 $otpService->sendVerificationOtp($user);
            } catch (\Exception $e) {
                 // Forward the cooldown message to the view
                 return redirect()->route('password.otp')->withErrors(['code' => $e->getMessage()]);
            }
        }

        return redirect()->route('password.otp');
    }
}

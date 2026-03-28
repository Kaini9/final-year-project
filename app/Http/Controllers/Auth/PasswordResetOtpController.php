<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordResetOtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-otp', ['email' => $request->session()->get('reset_email')]);
    }

    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        try {
            $otpService->validateOtp($user, $request->code);
            
            // Allow password reset
            $request->session()->put('password_reset_allowed_for', $user->email);
            
            return redirect()->route('password.reset');

        } catch (\Exception $e) {
            return back()->withErrors(['code' => $e->getMessage()]);
        }
    }
}

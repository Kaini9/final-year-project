<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\OtpService;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification (OTP).
     */
    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        try {
            $otpService->sendVerificationOtp($request->user());
            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}

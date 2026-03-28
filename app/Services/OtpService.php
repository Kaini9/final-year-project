<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthOtpMail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate, store, and send an OTP to the given user.
     */
    public function sendVerificationOtp(User $user)
    {
        // Check cooldown
        $existingOtp = $user->otps()->latest()->first();
        if ($existingOtp && $existingOtp->last_sent_at && Carbon::now()->diffInSeconds($existingOtp->last_sent_at) < 60) {
            throw new \Exception("Please wait 60 seconds before requesting a new code.");
        }

        // Generate 6-digit code
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate previous OTPs
        $user->otps()->delete();

        // Store new OTP
        $user->otps()->create([
            'code' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(10),
            'last_sent_at' => Carbon::now(),
            'attempts' => 0,
        ]);

        // Send Email
        Mail::to($user->email)->send(new AuthOtpMail($code, $user));
    }

    /**
     * Validate an OTP for a given user.
     */
    public function validateOtp(User $user, string $code): bool
    {
        $otp = $user->otps()->latest()->first();

        if (!$otp) {
            throw new \Exception("No active OTP found. Please request a new one.");
        }

        if (Carbon::now()->isAfter($otp->expires_at)) {
            $otp->delete();
            throw new \Exception("OTP has expired. Please request a new one.");
        }

        if ($otp->attempts >= 5) {
            $otp->delete();
            throw new \Exception("Too many invalid attempts. Please request a new OTP.");
        }

        if (!Hash::check($code, $otp->code)) {
            $otp->increment('attempts');
            throw new \Exception("Invalid verification code.");
        }

        // Successfully validated, delete OTP
        $otp->delete();

        return true;
    }
}

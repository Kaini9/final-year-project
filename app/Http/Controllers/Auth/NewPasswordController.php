<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;

class NewPasswordController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('password_reset_allowed_for')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => $request->session()->get('password_reset_allowed_for')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->email !== $request->session()->get('password_reset_allowed_for')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        event(new PasswordReset($user));

        $request->session()->forget('password_reset_allowed_for');
        $request->session()->forget('reset_email');

        return redirect()->route('login')->with('status', __('Your password has been securely reset!'));
    }
}

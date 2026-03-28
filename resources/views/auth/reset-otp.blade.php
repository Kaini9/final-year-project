<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('We have emailed a 6-digit verification code to your email address. Enter it below to authorize your password reset.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- OTP Code -->
        <div>
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input id="code" class="block mt-1 w-full tracking-[0.5em] text-center text-xl font-bold font-display text-ink uppercase" type="text" name="code" required autofocus pattern="[0-9]{6}" maxlength="6" inputmode="numeric" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <x-primary-button>
                {{ __('Verify Code') }}
            </x-primary-button>
        </div>
    </form>
    
    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div>
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Resend Code') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

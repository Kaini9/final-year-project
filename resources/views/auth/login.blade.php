<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="mb-6 p-4 text-sm text-rose-800 rounded-lg bg-rose-50 border border-rose-200" role="alert">
            <span class="font-bold uppercase tracking-widest text-xs">Access Denied</span> 
            <span class="ml-2">{{ session('error') }}</span>
        </div>
    @endif

    <div class="text-center mb-8">
        <h2 class="font-display text-3xl uppercase tracking-widest text-ink">Welcome Back</h2>
        <p class="text-sm text-gray-500 mt-2">Sign in to your creative workspace</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />

            <div class="relative mt-1">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink pr-12 transition-colors" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-ink transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-ink shadow-sm focus:ring-ink" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center py-3.5 rounded-lg text-sm tracking-widest uppercase font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transform transition-all">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-between mt-6 text-sm">
            @if (Route::has('password.request'))
                <a class="text-gray-500 hover:text-ink transition-colors font-semibold" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <a class="text-ink font-bold hover:underline" href="{{ route('register') }}">
                {{ __('Create account') }}
            </a>
        </div>
    </form>
</x-guest-layout>

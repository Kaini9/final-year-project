<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl uppercase tracking-widest text-ink">Join the Industry</h2>
        <p class="text-sm text-gray-500 mt-2">Create your creative portfolio account</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-5">
            <x-input-label for="role_id" :value="__('I am a...')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <select id="role_id" name="role_id" class="border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink rounded-lg shadow-sm block mt-1 w-full text-ink transition-colors" required>
                <option value="" disabled selected>Select your creative role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" :value="__('Email')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <div class="relative mt-1">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink pr-12 transition-colors" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-ink transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5" x-data="{ showPassword: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="uppercase tracking-widest text-xs font-bold text-gray-500" />
            <div class="relative mt-1">
                <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50/50 focus:bg-white focus:border-ink focus:ring-ink pr-12 transition-colors" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-ink transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center py-3.5 rounded-lg text-sm tracking-widest uppercase font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transform transition-all">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <a class="text-sm text-gray-500 hover:text-ink transition-colors font-semibold" href="{{ route('login') }}">
                {{ __('Already have an account?') }} <span class="text-ink font-bold">Sign in</span>
            </a>
        </div>
    </form>
</x-guest-layout>

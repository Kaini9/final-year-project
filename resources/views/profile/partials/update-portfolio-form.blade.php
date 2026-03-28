<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Professional Portfolio') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your designer/creator profile to showcase your work to the fashion community.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.portfolio.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        @php
            $profile = $user->profile;
            $skillsString = $profile && $profile->skills ? implode(', ', $profile->skills) : '';
            $instagram = $profile && $profile->social_links ? ($profile->social_links['instagram'] ?? '') : '';
            $linkedin = $profile && $profile->social_links ? ($profile->social_links['linkedin'] ?? '') : '';
            $website = $profile && $profile->social_links ? ($profile->social_links['website'] ?? '') : '';
        @endphp

        <!-- Avatar -->
        <div>
            <x-input-label for="avatar" :value="__('Avatar Image')" />
            @if($profile && $profile->avatar)
                <div class="mt-2 mb-4">
                    <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border border-gray-200">
                </div>
            @endif
            <input id="avatar" name="avatar" type="file" accept="image/*" class="block w-full text-sm text-gray-500
              file:mr-4 file:py-2 file:px-4
              file:rounded-md file:border-0
              file:text-sm file:font-semibold
              file:bg-ink file:text-white
              hover:file:bg-gray-800
            "/>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Biography')" />
            <textarea id="bio" name="bio" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('bio', $profile->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- Location -->
        <div>
            <x-input-label for="location" :value="__('Location')" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $profile->location ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        <!-- Skills -->
        <div>
            <x-input-label for="skills" :value="__('Skills (comma separated)')" />
            <x-text-input id="skills" name="skills" type="text" class="mt-1 block w-full" :value="old('skills', $skillsString)" placeholder="e.g. Editorial Styling, Runway, Editorial Makeup" />
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
        </div>

        <!-- Social Links -->
        <h3 class="text-md font-medium text-gray-900 pt-4 border-t border-gray-200">Social Links</h3>
        
        <div>
            <x-input-label for="social_instagram" :value="__('Instagram URL')" />
            <x-text-input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full" :value="old('social_instagram', $instagram)" placeholder="https://instagram.com/yourhandle" />
            <x-input-error class="mt-2" :messages="$errors->get('social_instagram')" />
        </div>

        <div>
            <x-input-label for="social_website" :value="__('Personal Website URL')" />
            <x-text-input id="social_website" name="social_website" type="url" class="mt-1 block w-full" :value="old('social_website', $website)" placeholder="https://yourportfolio.com" />
            <x-input-error class="mt-2" :messages="$errors->get('social_website')" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>{{ __('Save Portfolio') }}</x-primary-button>

            @if (session('status') === 'portfolio-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('jobs.show', $job) }}" class="text-[10px] font-bold text-gray-500 hover:text-ink mb-6 inline-block tracking-widest uppercase transition-colors">&larr; Back to Brief</a>

            <div class="bg-white border p-8 md:p-12 shadow-sm">
                <header class="mb-8 border-b pb-6 text-center">
                    <span class="inline-block px-3 py-1 bg-ink text-white text-[10px] font-bold uppercase tracking-widest mb-4">Application Form</span>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2 leading-tight">Pitch for {{ $job->title }}</h1>
                    <p class="text-sm text-gray-500 font-medium">The gig director will review this message alongside your connected portfolio.</p>
                </header>

                <div class="bg-gray-50 p-6 border mb-8 flex items-start gap-4">
                    <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden bg-gray-200 border">
                        @if(Auth::user()->profile && Auth::user()->profile->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500 font-display">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-ink">Applying as {{ Auth::user()->name }}</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-1">Role: {{ Auth::user()->role->name }}</p>
                    </div>
                </div>

                <form action="{{ route('job_applications.store', $job) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="message" :value="__('Your Pitch / Cover Message')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <textarea id="message" name="message" rows="8" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" required placeholder="Detail your experience, why you're perfect for the vision, and what you uniquely bring to the gig...">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        <p class="text-xs text-gray-400 mt-2">Maximum 2,000 characters.</p>
                    </div>

                    <div class="pt-8 flex justify-end">
                        <x-primary-button class="uppercase tracking-widest font-bold px-8 py-4 bg-ink hover:bg-gray-800 shadow-md hover:-translate-y-0.5 transform transition-all">
                            {{ __('Submit Portfolio & Pitch') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>

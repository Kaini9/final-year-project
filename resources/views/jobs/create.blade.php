<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Marketplace</a>

            <div class="bg-white border p-8 md:p-12 shadow-sm">
                <header class="mb-8 border-b pb-6">
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2">Post Opportunity</h1>
                    <p class="text-sm text-gray-500 font-medium">Create a detailed casting call or gig brief to find the perfect creative for your vision.</p>
                </header>

                <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Opportunity Title')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="old('title')" required autofocus placeholder="e.g. Lead Model for Vanguard FW27 Editorial" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role Required -->
                        <div>
                            <x-input-label for="role_required" :value="__('Looking For')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                            <select id="role_required" name="role_required" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink text-sm py-3" required>
                                <option value="" disabled selected>Select a professional role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role_required') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_required')" class="mt-2" />
                        </div>

                        <!-- Budget -->
                        <div>
                            <x-input-label for="budget" :value="__('Budget (NRS)')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="old('budget')" placeholder="e.g. 5000.00 (Optional)" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <x-input-label for="deadline" :value="__('Application Deadline')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="old('deadline')" />
                        <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="pt-4">
                        <x-input-label for="description" :value="__('Detailed Brief & Requirements')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <textarea id="description" name="description" rows="8" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" required placeholder="Describe the project vision, shoot date, location, required aesthetics, and expectations..."></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="pt-8 border-t flex justify-end">
                        <x-primary-button class="uppercase tracking-widest font-semibold px-8 py-4">
                            {{ __('Publish Opportunity') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>

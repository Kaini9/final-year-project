<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('jobs.show', $job) }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Cancel Edits</a>

            <div class="bg-white border p-8 md:p-12 shadow-sm">
                <header class="mb-8 border-b pb-6">
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2">Edit Opportunity</h1>
                    <p class="text-sm text-gray-500 font-medium">Update the details of your casting call or gig.</p>
                </header>

                <form action="{{ route('jobs.update', $job) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Opportunity Title')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="old('title', $job->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Role Required -->
                        <div>
                            <x-input-label for="role_required" :value="__('Looking For')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                            <select id="role_required" name="role_required" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink text-sm py-3" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role_required', $job->role_required) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_required')" class="mt-2" />
                        </div>

                        <!-- Budget -->
                        <div>
                            <x-input-label for="budget" :value="__('Budget (NRS)')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="old('budget', $job->budget)" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Listing Status')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink text-sm py-3" required>
                                <option value="active" {{ old('status', $job->status) === 'active' ? 'selected' : '' }}>Active (Open)</option>
                                <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>Closed (Filled)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <x-input-label for="deadline" :value="__('Application Deadline')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" :value="\Carbon\Carbon::parse(old('deadline', $job->deadline))->format('Y-m-d')" />
                        <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="pt-4">
                        <x-input-label for="description" :value="__('Detailed Brief & Requirements')" class="uppercase tracking-widest text-xs font-bold mb-2 text-gray-700" />
                        <textarea id="description" name="description" rows="8" class="mt-1 block w-full border-gray-300 rounded-none shadow-sm focus:border-ink focus:ring-ink" required>{{ old('description', $job->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="pt-8 border-t flex justify-end">
                        <x-primary-button class="uppercase tracking-widest font-semibold px-8 py-4 bg-ink hover:bg-gray-800">
                            {{ __('Update Opportunity') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>

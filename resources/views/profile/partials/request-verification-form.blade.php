<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">
            {{ __('Account Verification') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Apply for a verified badge to increase trust and visibility on FashionConnect.") }}
        </p>
    </header>

    @php
        $verification = Auth::user()->verification;
    @endphp

    @if($verification && $verification->is_active_badge)
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
            </div>
            <div>
                <p class="font-bold text-emerald-800 uppercase tracking-widest text-xs">Account Verified</p>
                <p class="text-[10px] text-emerald-600 mt-0.5">Your identity is verified. Renews {{ $verification->expires_at ? $verification->expires_at->format('M j, Y') : 'soon' }}.</p>
            </div>
        </div>
    @elseif($verification && $verification->status === 'approved' && !$verification->is_active_badge)
        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-indigo-800 uppercase tracking-widest text-xs">Verification Expired / Unpaid</p>
                    <p class="text-[10px] text-indigo-600 mt-0.5">Your previous verification needs renewal.</p>
                </div>
            </div>
            <a href="{{ route('khalti.initiate', $verification) }}" class="px-4 py-2 bg-[#5C2D91] text-white text-xs font-bold rounded hover:bg-[#4d2678] transition-colors shadow-sm whitespace-nowrap">Pay Rs. 200 via Khalti</a>
        </div>
    @elseif($verification && $verification->status === 'pending' && $verification->payment_status === 'paid')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="font-bold text-amber-800 uppercase tracking-widest text-xs">Under Review</p>
                <p class="text-[10px] text-amber-600 mt-0.5">Payment received. Your request is currently being reviewed by admins.</p>
            </div>
        </div>
    @elseif($verification && $verification->status === 'pending' && $verification->payment_status === 'unpaid')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-amber-800 uppercase tracking-widest text-xs">Awaiting Payment</p>
                    <p class="text-[10px] text-amber-600 mt-0.5">Please complete the Rs. 200 payment to submit your request.</p>
                </div>
            </div>
            <a href="{{ route('khalti.initiate', $verification) }}" class="px-4 py-2 bg-[#5C2D91] text-white text-xs font-bold rounded hover:bg-[#4d2678] transition-colors shadow-sm whitespace-nowrap">Pay via Khalti</a>
        </div>
    @else
        @if($verification && $verification->status === 'rejected')
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-lg flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-rose-800 uppercase tracking-widest text-xs">Application Rejected</p>
                    <p class="text-[10px] text-rose-600 mt-0.5">Your previous request was declined. You may re-apply below.</p>
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('verifications.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <!-- Social/Portfolio Link -->
            <div>
                <x-input-label for="social_link" :value="__('Portfolio or Social Media Link')" class="uppercase tracking-widest text-[10px] font-bold" />
                <x-text-input id="social_link" name="social_link" type="url" class="mt-1 block w-full text-sm" placeholder="https://instagram.com/yourhandle" required />
                <x-input-error :messages="$errors->get('social_link')" class="mt-2 text-xs" />
                <p class="text-[10px] text-gray-500 mt-1">Link to your professional portfolio, agency roster, or established social media presence.</p>
            </div>

            <!-- Passport Photo Upload with Preview -->
            <div x-data="{ photoPreview: null }">
                <x-input-label for="document" :value="__('Passport Size Photo')" class="uppercase tracking-widest text-[10px] font-bold" />
                
                <div class="mt-2 flex items-center gap-6">
                    <!-- Photo Preview Frame -->
                    <div class="w-24 h-32 shrink-0 bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative">
                        <!-- Placeholder -->
                        <div x-show="!photoPreview" class="text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <!-- Preview Image -->
                        <span class="block w-full h-full bg-cover bg-no-repeat bg-center absolute inset-0" x-show="photoPreview" :style="'background-image: url(\'' + photoPreview + '\');'" style="display: none;"></span>
                    </div>

                    <!-- Input Button -->
                    <div class="flex-grow">
                        <input id="document" name="document" type="file" required accept=".img,.jpg,.jpeg,.png" class="hidden" 
                            x-ref="photo"
                            x-on:change="
                                const file = $refs.photo.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            "
                        />
                        
                        <button type="button" x-on:click.prevent="$refs.photo.click()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold uppercase tracking-widest shadow-sm rounded transition-colors">
                            Select Photo
                        </button>
                        
                        <p class="text-[10px] text-gray-500 mt-2">Clear, front-facing passport style photo. Max 2MB (JPG, PNG).</p>
                        <x-input-error :messages="$errors->get('document')" class="mt-2 text-xs" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Application Fee: Rs. 200 / month</p>
                <button type="submit" class="px-6 py-3 bg-[#5C2D91] text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-[#4d2678] transition-colors shadow-sm">
                    Checkout with Khalti
                </button>
            </div>
        </form>
    @endif
</section>

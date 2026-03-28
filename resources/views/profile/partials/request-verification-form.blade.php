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

    @if($verification && $verification->status === 'approved')
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
            </div>
            <div>
                <p class="font-bold text-emerald-800 uppercase tracking-widest text-xs">Account Verified</p>
                <p class="text-[10px] text-emerald-600 mt-0.5">Your identity and portfolio have been verified by our team.</p>
            </div>
        </div>
    @elseif($verification && $verification->status === 'pending')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="font-bold text-amber-800 uppercase tracking-widest text-xs">Under Review</p>
                <p class="text-[10px] text-amber-600 mt-0.5">Your verification request is currently being reviewed by admins.</p>
            </div>
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

        <form method="post" action="{{ route('verification.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <!-- Social/Portfolio Link -->
            <div>
                <x-input-label for="social_link" :value="__('Portfolio or Social Media Link')" class="uppercase tracking-widest text-[10px] font-bold" />
                <x-text-input id="social_link" name="social_link" type="url" class="mt-1 block w-full text-sm" placeholder="https://instagram.com/yourhandle" required />
                <x-input-error :messages="$errors->get('social_link')" class="mt-2 text-xs" />
                <p class="text-[10px] text-gray-500 mt-1">Link to your professional portfolio, agency roster, or established social media presence.</p>
            </div>

            <!-- Identity Document Upload -->
            <div>
                <x-input-label for="document" :value="__('Proof of Identity (Optional)')" class="uppercase tracking-widest text-[10px] font-bold" />
                <input id="document" name="document" type="file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-ink hover:file:bg-gray-200" />
                <x-input-error :messages="$errors->get('document')" class="mt-2 text-xs" />
                <p class="text-[10px] text-gray-500 mt-1">Upload a government ID or official agency comp card (Max 2MB. PDF, JPG, PNG).</p>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <x-primary-button>{{ __('Submit Application') }}</x-primary-button>

                @if (session('verification_status') === 'submitted')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 4000)"
                        class="text-sm text-gray-600"
                    >{{ __('Submitted.') }}</p>
                @endif
            </div>
        </form>
    @endif
</section>

<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto">
            <!-- Back button -->
            <a href="{{ route('profile.settings') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Settings</a>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-[#5C2D91] px-8 py-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm -mt-2">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1 font-display tracking-widest uppercase">Payment Successful</h2>
                    <p class="text-white/80 text-sm">Your application fee has been received.</p>
                </div>

                <!-- Receipt Details -->
                <div class="p-8">
                    <div class="flex justify-between items-end mb-8 block pb-6 border-b border-dashed border-gray-200">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Amount Paid</p>
                            <h3 class="text-4xl font-bold text-ink">Rs. 200</h3>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-widest">
                                Paid via Khalti
                            </span>
                        </div>
                    </div>

                    <dl class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 font-medium">Transaction ID</dt>
                            <dd class="text-ink font-mono font-bold">{{ $verification->khalti_pidx }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 font-medium">Date</dt>
                            <dd class="text-ink font-bold">{{ $verification->updated_at->format('F j, Y, g:i A') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 font-medium">Billed To</dt>
                            <dd class="text-ink font-bold">{{ $verification->user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 font-medium">Description</dt>
                            <dd class="text-ink font-bold">Verified Badge Application Fee</dd>
                        </div>
                    </dl>
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row gap-4 justify-center items-center border-t border-gray-100">
                    <a href="{{ route('khalti.receipt.download', $verification) }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-200 text-ink text-xs font-bold uppercase tracking-widest rounded hover:bg-gray-50 transition-colors shadow-sm text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

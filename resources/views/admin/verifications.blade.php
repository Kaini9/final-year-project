<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Dashboard</a>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-1">Verification Requests</h1>
                    <p class="text-sm text-gray-500 font-medium">Review and process applications for the verified blue tick.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 text-sm text-green-800 rounded-lg bg-green-50 bg-white border border-green-200" role="alert">
                    <span class="font-bold uppercase tracking-widest text-xs">Success</span> <span class="ml-2">{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase tracking-widest font-bold text-gray-500 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-4">User</th>
                                <th scope="col" class="px-6 py-4">Submitted Link</th>
                                <th scope="col" class="px-6 py-4">Passport Photo</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Submitted</th>
                                <th scope="col" class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($verifications as $verification)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($verification->user->profile && $verification->user->profile->avatar)
                                                <img src="{{ Storage::url($verification->user->profile->avatar) }}" alt="{{ $verification->user->name }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
                                                    <span class="text-gray-500 font-bold text-sm">{{ substr($verification->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('profile.show', $verification->user->id) }}" target="_blank" class="font-bold text-ink hover:underline">{{ $verification->user->name }}</a>
                                                <div class="text-xs text-gray-500">{{ $verification->user->role->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($verification->social_link)
                                            <a href="{{ $verification->social_link }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900 font-medium break-all max-w-[200px] inline-block truncate">
                                                {{ $verification->social_link }}
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($verification->document_path)
                                            <div class="group relative w-16 h-20 bg-gray-100 border border-gray-200 rounded cursor-pointer overflow-hidden">
                                                <img src="{{ Storage::url($verification->document_path) }}" alt="Passport Photo" class="w-full h-full object-cover">
                                                <a href="{{ Storage::url($verification->document_path) }}" target="_blank" class="absolute inset-0 bg-black/50 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity" title="View Full">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No photo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="block">
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full 
                                                {{ $verification->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $verification->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $verification->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}
                                            ">
                                                App: {{ $verification->status }}
                                            </span>
                                            <br>
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full mt-2 inline-block
                                                {{ $verification->payment_status === 'paid' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}
                                            ">
                                                Fee: {{ $verification->payment_status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap text-gray-500">
                                        {{ $verification->created_at->format('M j, Y') }}
                                        @if($verification->expires_at)
                                            <div class="text-[10px] font-bold text-gray-400 mt-1.5 uppercase tracking-widest" title="Valid until">EXP: {{ $verification->expires_at->format('M j, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($verification->status === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                @if($verification->payment_status === 'paid')
                                                    <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded text-xs font-bold uppercase tracking-widest transition-colors">
                                                            Approve
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[9px] text-amber-600 font-bold uppercase tracking-widest mr-2 bg-amber-50 px-2 py-1 rounded">Awaiting Payment</span>
                                                @endif
                                                <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this verification request?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded text-xs font-bold uppercase tracking-widest transition-colors">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($verification->status === 'approved')
                                            <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to revoke this verified status?');">
                                                @csrf
                                                <button type="submit" class="text-[10px] text-gray-400 hover:text-rose-600 font-bold uppercase tracking-widest transition-colors">
                                                    Revoke
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="w-12 h-12 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="font-medium text-sm">No verification requests found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $verifications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

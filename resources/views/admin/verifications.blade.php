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
                                <th scope="col" class="px-6 py-4">ID / Document</th>
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
                                            <a href="{{ Storage::url($verification->document_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-bold uppercase tracking-widest transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                View ID
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No document</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full 
                                            {{ $verification->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                            {{ $verification->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $verification->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}
                                        ">
                                            {{ $verification->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap text-gray-500">
                                        {{ $verification->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($verification->status === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded text-xs font-bold uppercase tracking-widest transition-colors">
                                                        Approve
                                                    </button>
                                                </form>
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

<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-8">My Applications</h1>

            @if($applications->count() > 0)
                <div class="space-y-4">
                    @foreach($applications as $application)
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center gap-5">
                            
                            <!-- Gig Info -->
                            <div class="flex-grow min-w-0">
                                <a href="{{ route('jobs.show', $application->job) }}" class="font-bold text-lg text-ink hover:underline tracking-tight block truncate">{{ $application->job->title }}</a>
                                <div class="flex items-center gap-3 mt-1.5 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full overflow-hidden bg-gray-200 border">
                                            @if($application->job->user->profile && $application->job->user->profile->avatar)
                                                <img src="{{ asset('storage/' . $application->job->user->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="w-full h-full flex items-center justify-center font-display text-[8px]">{{ substr($application->job->user->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <span class="font-semibold">{{ $application->job->user->name }}</span>
                                    </div>
                                    <span>&bull;</span>
                                    <span>{{ $application->job->role_required }}</span>
                                    <span>&bull;</span>
                                    <span>Applied {{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="shrink-0 flex items-center gap-3">
                                @if($application->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-bold uppercase tracking-widest rounded-full">
                                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                        Under Review
                                    </span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-50 text-green-700 border border-green-200 text-xs font-bold uppercase tracking-widest rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Accepted
                                    </span>
                                    <a href="{{ route('messages.show', $application->job->user) }}" class="px-4 py-2 bg-ink text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-gray-800 transition-colors shadow-sm">
                                        Message
                                    </a>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 border border-red-200 text-xs font-bold uppercase tracking-widest rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Not Selected
                                    </span>
                                @endif

                                <!-- Withdraw -->
                                @if($application->status === 'pending')
                                    <form action="{{ route('job_applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Withdraw this application?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] text-gray-400 font-bold uppercase tracking-widest hover:text-red-500 transition-colors underline">Withdraw</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-16 text-center flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="font-display text-xl uppercase tracking-widest text-gray-400 mb-2">No Applications Yet</h3>
                    <p class="text-sm text-gray-500">Browse the <a href="{{ route('jobs.index') }}" class="text-ink font-bold hover:underline">Marketplace</a> to find gigs and submit your first proposal.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Mobile Header -->
            <div class="flex justify-between items-center sm:hidden mb-4">
                <h1 class="font-display text-3xl uppercase tracking-widest text-ink">Marketplace</h1>
                @role('Designer')
                    <a href="{{ route('jobs.create') }}" class="px-4 py-2 bg-ink text-white text-xs font-semibold uppercase tracking-widest hover:bg-gray-800 transition-colors">Post Gig</a>
                @endrole
            </div>

            <!-- Desktop Header -->
            <div class="hidden sm:flex justify-between items-center bg-white border p-8 shadow-sm">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2">Opportunities</h1>
                    <p class="text-sm text-gray-500 font-medium">Discover and apply to casting calls, collaborations, and paid gigs.</p>
                </div>
                @role('Designer')
                    <a href="{{ route('jobs.create') }}" class="px-6 py-3 bg-ink text-white text-sm font-semibold uppercase tracking-widest hover:bg-gray-800 transition-colors shadow-sm">Post Opportunity</a>
                @endrole
            </div>

            <!-- Verification Messages -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 bg-white border border-green-200" role="alert">
                    <span class="font-medium">Success!</span> {{ session('status') }}
                </div>
            @endif

            <!-- Jobs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @forelse($jobs as $job)
                    <div class="bg-white border text-ink p-6 hover:shadow-lg transition-shadow flex flex-col justify-between group cursor-pointer" onclick="window.location='{{ route('jobs.show', $job) }}'">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-gray-100 text-xs font-bold uppercase tracking-wider text-gray-800 border">{{ $job->role_required }}</span>
                                <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="font-bold text-xl mb-3 line-clamp-2 group-hover:underline"><a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a></h2>
                            <p class="text-sm text-gray-600 mb-6 line-clamp-3 leading-relaxed">{{ $job->description }}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 border flex items-center justify-center font-display text-gray-500">
                                    @if($job->user->profile && $job->user->profile->avatar)
                                        <img src="{{ asset('storage/' . $job->user->profile->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($job->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="block text-xs font-bold leading-none">{{ $job->user->name }}</span>
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-widest mt-1">Designer</span>
                                </div>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="text-xs font-bold text-ink hover:text-gray-500 uppercase tracking-wide transition-colors">View &rarr;</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center text-gray-500 bg-white border-2 border-dashed border-gray-200">
                        <p class="font-display text-3xl uppercase tracking-widest mb-3 text-gray-400">No opportunities yet.</p>
                        <p class="text-sm">Check back later for new casting calls and creative gigs.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 pb-12">
                {{ $jobs->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>

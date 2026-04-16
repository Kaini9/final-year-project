<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Mobile Header -->
            <div class="flex justify-between items-center sm:hidden mb-4">
                <h1 class="font-display text-3xl uppercase tracking-widest text-ink">Marketplace</h1>
                <div class="flex gap-2">
                    <a href="{{ route('job_applications.mine') }}" class="px-3 py-2 bg-white text-ink border border-gray-200 text-xs font-semibold uppercase tracking-widest hover:bg-gray-50 transition-colors">My Apps</a>
                    @role('Designer')
                        <a href="{{ route('jobs.create') }}" class="px-3 py-2 bg-ink text-white text-xs font-semibold uppercase tracking-widest hover:bg-gray-800 transition-colors">Post Gig</a>
                    @endrole
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden sm:flex justify-between items-center bg-white border p-8 shadow-sm">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2">Opportunities</h1>
                    <p class="text-sm text-gray-500 font-medium">Discover and apply to casting calls, collaborations, and paid gigs.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('job_applications.mine') }}" class="px-5 py-3 bg-white text-ink border border-gray-200 text-sm font-semibold uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-sm">My Applied Gigs</a>
                    @role('Designer')
                        <a href="{{ route('jobs.create') }}" class="px-6 py-3 bg-ink text-white text-sm font-semibold uppercase tracking-widest hover:bg-gray-800 transition-colors shadow-sm">Post Opportunity</a>
                    @endrole
                </div>
            </div>

            <!-- Verification Messages -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 bg-white border border-green-200" role="alert">
                    <span class="font-medium">Success!</span> {{ session('status') }}
                </div>
            @endif

            <!-- Search & Filter Bar -->
            <div class="bg-white border p-4 shadow-sm mb-6 mt-4 md:mt-0">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow relative border bg-gray-50 flex items-center">
                        <div class="pl-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <label for="search" class="sr-only">Search Opportunities</label>
                        <input id="search" name="search" type="text" class="block w-full text-sm border-0 focus:ring-0 bg-transparent text-ink placeholder-gray-400 py-3" placeholder="Search by keyword, brand, or title..." value="{{ request('search') }}" />
                    </div>
                    
                    <div class="sm:w-64 shrink-0 relative border bg-gray-50">
                        <label for="role" class="sr-only">Filter by Role</label>
                        <select id="role" name="role" class="block w-full text-sm border-0 focus:ring-0 bg-transparent py-3 uppercase tracking-widest text-gray-700 font-bold px-4 appearance-none">
                            <option value="">All Roles</option>
                            @if(isset($roles))
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <button type="submit" class="justify-center sm:w-auto w-full px-8 py-3 bg-ink text-white text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors shadow-sm">
                        Search
                    </button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('jobs.index') }}" class="justify-center flex items-center sm:w-auto w-full px-6 py-3 bg-white border border-gray-200 text-gray-500 text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-sm">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Jobs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @forelse($jobs as $job)
                    <div class="bg-white border text-ink p-6 hover:shadow-lg transition-all flex flex-col justify-between group cursor-pointer" onclick="window.location='{{ route('jobs.show', $job) }}'">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-ink text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">{{ $job->role_required }}</span>
                                <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="font-bold text-xl mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors"><a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a></h2>
                            
                            @if($job->budget)
                                <div class="mb-4 inline-flex items-center px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-widest rounded border border-green-200">
                                    Budget: Rs. {{ number_format($job->budget) }}
                                </div>
                            @elseif($job->budget === null)
                                <div class="mb-4 inline-flex items-center px-2.5 py-1 bg-gray-50 text-gray-600 text-[10px] font-bold uppercase tracking-widest rounded border border-gray-200">
                                    Unpaid / Collab
                                </div>
                            @endif

                            <p class="text-sm text-gray-600 mb-6 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags(nl2br(e($job->description))), 150) }}</p>
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
                                    <span class="block text-xs font-bold leading-none flex items-center gap-1">
                                        {{ $job->user->name }}
                                        @if($job->user->is_verified)
                                            <svg class="w-3 h-3 text-ink" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </span>
                                    <span class="block text-[10px] text-gray-500 uppercase tracking-widest mt-1">Creator</span>
                                </div>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="text-[10px] font-bold text-ink hover:text-gray-500 uppercase tracking-widest transition-colors bg-gray-50 px-3 py-1.5 border rounded">View &rarr;</a>
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

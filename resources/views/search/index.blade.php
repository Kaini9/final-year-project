<x-app-layout>
    <div class="bg-ivory min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <header class="mb-8">
                <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-2">Search Results</h1>
                <p class="text-sm text-gray-500 font-medium">Found results for "<span class="text-ink font-bold">{{ $q }}</span>"</p>
            </header>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-8 overflow-x-auto">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('search', ['q' => $q, 'type' => 'all']) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest {{ $type === 'all' ? 'border-ink text-ink' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">All Results</a>
                    
                    <a href="{{ route('search', ['q' => $q, 'type' => 'users']) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest {{ $type === 'users' ? 'border-ink text-ink' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        People <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-[10px]">{{ $type === 'users' ? $users->total() : $users->count() }}{{ ($type === 'all' && $users->count() === 6) ? '+' : '' }}</span>
                    </a>
                    
                    <a href="{{ route('search', ['q' => $q, 'type' => 'jobs']) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest {{ $type === 'jobs' ? 'border-ink text-ink' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Opportunities <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-[10px]">{{ $type === 'jobs' ? $jobs->total() : $jobs->count() }}{{ ($type === 'all' && $jobs->count() === 5) ? '+' : '' }}</span>
                    </a>

                    <a href="{{ route('search', ['q' => $q, 'type' => 'posts']) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest {{ $type === 'posts' ? 'border-ink text-ink' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Posts <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-[10px]">{{ $type === 'posts' ? $posts->total() : $posts->count() }}{{ ($type === 'all' && $posts->count() === 10) ? '+' : '' }}</span>
                    </a>
                </nav>
            </div>

            @if(empty($q))
                <div class="text-center py-20 bg-white border border-dashed border-gray-300 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <p class="text-sm font-bold uppercase tracking-widest">Type something to search...</p>
                </div>
            @else

                <!-- Users Section -->
                @if($type === 'all' || $type === 'users')
                    <div class="mb-12">
                        @if($type === 'all')
                            <div class="flex justify-between items-center mb-6 border-b pb-2">
                                <h2 class="text-lg font-display uppercase tracking-widest text-ink">People</h2>
                                @if($users->count() === 6)
                                    <a href="{{ route('search', ['q' => $q, 'type' => 'users']) }}" class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest hover:underline">View All &rarr;</a>
                                @endif
                            </div>
                        @endif

                        @if($users->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($type === 'all' ? $users->take(6) : $users as $user)
                                    <div class="bg-white border p-6 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer group" onclick="window.location='{{ route('profile.show', $user) }}'">
                                        <div class="w-14 h-14 shrink-0 rounded-full overflow-hidden bg-gray-100 border flex items-center justify-center font-display text-gray-400">
                                            @if($user->profile && $user->profile->avatar)
                                                <img src="{{ asset('storage/' . $user->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="block">
                                            <h3 class="font-bold text-ink group-hover:text-indigo-600 transition-colors flex items-center gap-1">
                                                {{ $user->name }}
                                                @if($user->verification && $user->verification->status === 'approved')
                                                    <x-verified-badge />
                                                @endif
                                            </h3>
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $user->role->name }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($type === 'users')
                                <div class="mt-6">{{ $users->links() }}</div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 italic py-4">No designers or brands matched your search.</p>
                        @endif
                    </div>
                @endif

                <!-- Opportunities Section -->
                @if($type === 'all' || $type === 'jobs')
                    <div class="mb-12 {{ $type === 'all' ? 'mt-12' : '' }}">
                        @if($type === 'all')
                            <div class="flex justify-between items-center mb-6 border-b pb-2">
                                <h2 class="text-lg font-display uppercase tracking-widest text-ink">Opportunities</h2>
                                @if($jobs->count() === 5)
                                    <a href="{{ route('search', ['q' => $q, 'type' => 'jobs']) }}" class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest hover:underline">View All &rarr;</a>
                                @endif
                            </div>
                        @endif

                        @if($jobs->count() > 0)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($type === 'all' ? $jobs->take(4) : $jobs as $job)
                                    <div class="bg-white border text-ink p-6 hover:shadow-md transition-all cursor-pointer group" onclick="window.location='{{ route('jobs.show', $job) }}'">
                                        <div class="flex justify-between items-start mb-3">
                                            <span class="px-2 py-0.5 bg-ink text-white text-[9px] font-bold uppercase tracking-wider shadow-sm">{{ $job->role_required }}</span>
                                            <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $job->created_at->diffForHumans() }}</span>
                                        </div>
                                        <h3 class="font-bold text-lg mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $job->title }}</h3>
                                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ strip_tags(nl2br(e($job->description))) }}</p>
                                        
                                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                                            <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-100 border text-gray-400 font-display flex justify-center items-center text-[10px]">
                                                @if($job->user->profile && $job->user->profile->avatar)
                                                    <img src="{{ asset('storage/' . $job->user->profile->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($job->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <span class="text-xs font-bold text-ink">{{ $job->user->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($type === 'jobs')
                                <div class="mt-6">{{ $jobs->links() }}</div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 italic py-4">No opportunities found for that query.</p>
                        @endif
                    </div>
                @endif

                <!-- Posts Section -->
                @if($type === 'all' || $type === 'posts')
                    <div class="mb-12 {{ $type === 'all' ? 'mt-12' : '' }}">
                        @if($type === 'all')
                            <div class="flex justify-between items-center mb-6 border-b pb-2">
                                <h2 class="text-lg font-display uppercase tracking-widest text-ink">Posts</h2>
                                @if($posts->count() === 10)
                                    <a href="{{ route('search', ['q' => $q, 'type' => 'posts']) }}" class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest hover:underline">View All &rarr;</a>
                                @endif
                            </div>
                        @endif

                        @if($posts->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($type === 'all' ? $posts->take(6) : $posts as $post)
                                    <div class="bg-white border p-6 hover:shadow-sm">
                                        <div class="flex items-center gap-3 mb-4 border-b pb-4">
                                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 border text-gray-400 font-display flex items-center justify-center text-xs">
                                                @if($post->user->profile && $post->user->profile->avatar)
                                                    <img src="{{ asset('storage/' . $post->user->profile->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($post->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('profile.show', $post->user) }}" class="text-xs font-bold text-ink hover:underline">{{ $post->user->name }}</a>
                                                <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-relaxed line-clamp-4">{{ $post->caption }}</p>
                                        
                                        @if($post->image)
                                            <div class="mt-4 rounded border bg-gray-100 h-32 overflow-hidden border flex items-center justify-center">
                                                <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($type === 'posts')
                                <div class="mt-6">{{ $posts->links() }}</div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 italic py-4">No social posts matched.</p>
                        @endif
                    </div>
                @endif

            @endif
        </div>
    </div>
</x-app-layout>

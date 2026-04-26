<x-app-layout>
    <div class="bg-ivory min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Profile Header Card -->
            <div class="bg-white border text-ink p-8 md:p-12 mb-12 flex flex-col md:flex-row items-center md:items-start gap-8 shadow-sm">
                <!-- Avatar -->
                <div class="w-32 h-32 md:w-48 md:h-48 flex-shrink-0">
                    @if($user->profile && $user->profile->avatar)
                        <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover border border-gray-200">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400">
                            <span class="text-4xl font-display">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-grow text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start mb-2 gap-3">
                        <h1 class="font-display text-5xl md:text-6xl uppercase tracking-wider">{{ $user->name }}</h1>
                        @if($user->is_verified)
                            <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-md border-2 border-white" title="Verified Member">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-4">
                        <span class="inline-block px-3 py-1 bg-ink text-white text-xs tracking-widest uppercase font-semibold">
                            {{ $user->role->name }}
                        </span>

                        @if(Auth::id() !== $user->id)
                            <form action="{{ route('user.follow', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-block px-4 py-1 {{ $isFollowing ? 'bg-white text-ink border border-ink hover:bg-gray-50' : 'bg-ink text-white hover:bg-gray-800' }} text-xs tracking-widest uppercase font-semibold transition-colors">
                                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                            <a href="{{ route('messages.show', $user) }}" class="inline-block px-4 py-1 bg-white text-ink border border-gray-300 hover:bg-gray-50 text-xs tracking-widest uppercase font-semibold transition-colors">
                                Message
                            </a>
                        @endif

                        @if($user->profile && $user->profile->location)
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $user->profile->location }}
                            </span>
                        @endif
                    </div>

                    @if($user->profile && $user->profile->bio)
                        <p class="text-gray-600 max-w-2xl leading-relaxed mb-6">
                            {!! nl2br(e($user->profile->bio)) !!}
                        </p>
                    @endif

                    <!-- Skills -->
                    @if($user->profile && $user->profile->skills)
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-6">
                            @foreach($user->profile->skills as $skill)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm border border-gray-200">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Socials -->
                    @if($user->profile && $user->profile->social_links)
                        <div class="flex items-center justify-center md:justify-start gap-4 mb-6">
                            @foreach($user->profile->social_links as $network => $url)
                                <a href="{{ $url }}" target="_blank" class="text-sm font-semibold uppercase tracking-wider text-gray-500 hover:text-ink transition-colors underline-offset-4 hover:underline">
                                    {{ $network }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center justify-center md:justify-start gap-8 pt-4 border-t border-gray-100">
                        <div class="text-center">
                            <span class="block font-display text-2xl text-ink">{{ $user->followers()->count() }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Followers</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-display text-2xl text-ink">{{ $user->following()->count() }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Following</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-display text-2xl text-ink">{{ $user->posts()->count() }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Posts</span>
                        </div>
                        @if($user->hasRole('Designer') || ($user->role && $user->role->can_post_jobs) || $user->jobs()->count() > 0)
                            <div class="text-center">
                                <span class="block font-display text-2xl text-ink">{{ $user->jobs()->count() }}</span>
                                <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Gigs</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Grid for Jobs -->
            @if($jobs->count() > 0 || $user->hasRole('Designer') || ($user->role && $user->role->can_post_jobs))
                <div class="mb-12">
                    <h2 class="font-display text-3xl uppercase tracking-widest mb-8 border-b pb-4">
                        GIGS
                    </h2>

                    @if($jobs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($jobs as $job)
                                <a href="{{ route('jobs.show', $job) }}" class="block bg-white p-6 border hover:shadow-md transition-shadow">
                                    <div class="text-xs text-gray-500 mb-2 uppercase tracking-wide">{{ $job->created_at->diffForHumans() }}</div>
                                    <h3 class="font-bold text-xl mb-2">{{ $job->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $job->description }}</p>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="bg-gray-100 px-2 py-1">{{ $job->role_required }}</span>
                                        <span class="text-indigo-600 font-semibold cursor-pointer">View &rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No gigs posted yet.</p>
                    @endif
                </div>
            @endif

            <!-- Content Grid for Posts -->
            <div>
                <h2 class="font-display text-3xl uppercase tracking-widest mb-8 border-b pb-4">
                    PORTFOLIO / POSTS
                </h2>

                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($posts as $post)
                            @php
                                $firstImage = null;
                                if (!empty($post->images) && is_array($post->images) && count($post->images) > 0) {
                                    $firstImage = $post->images[0];
                                } elseif (!empty($post->image)) {
                                    $firstImage = $post->image;
                                }
                            @endphp
                            <a href="{{ route('posts.show', $post) }}" class="block aspect-square bg-gray-100 border relative group overflow-hidden">
                                @if($firstImage)
                                    @php
                                        $imgUrl = strpos($firstImage, 'http') === 0 ? $firstImage : (strpos($firstImage, 'storage/') === 0 ? $firstImage : 'storage/' . $firstImage);
                                    @endphp
                                    <img src="{{ asset($imgUrl) }}" alt="Post image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-ink/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4 text-white">
                                    <p class="text-sm line-clamp-2">{{ $post->caption }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No posts uploaded yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

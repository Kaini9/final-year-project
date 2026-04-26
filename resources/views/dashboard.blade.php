<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-8">
                
                <!-- LEFT SIDEBAR (desktop only) -->
                <div class="hidden lg:block w-72 shrink-0 space-y-6 sticky top-24 self-start">
                    <!-- Profile Quick Card -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 border mb-3">
                            @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 font-display text-2xl">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <h3 class="font-bold text-ink tracking-tight">{{ Auth::user()->name }}</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ Auth::user()->role->name }}</p>
                        <div class="flex justify-center gap-6 mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <span class="block font-display text-lg text-ink">{{ Auth::user()->followers()->count() }}</span>
                                <span class="text-[9px] uppercase tracking-widest text-gray-400 font-bold">Followers</span>
                            </div>
                            <div class="text-center">
                                <span class="block font-display text-lg text-ink">{{ Auth::user()->following()->count() }}</span>
                                <span class="text-[9px] uppercase tracking-widest text-gray-400 font-bold">Following</span>
                            </div>
                        </div>
                        <a href="{{ route('profile.show', Auth::user()) }}" class="block mt-4 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-ink transition-colors">View Profile &rarr;</a>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Quick Links</h4>
                        <nav class="space-y-1">
                            <a href="{{ route('jobs.index') }}" class="flex items-center gap-2.5 text-sm font-semibold text-gray-600 hover:text-ink py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Marketplace
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center gap-2.5 text-sm font-semibold text-gray-600 hover:text-ink py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Inbox
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 text-sm font-semibold text-gray-600 hover:text-ink py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Setup
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- CENTER: Main Feed -->
                <div class="flex-grow max-w-2xl mx-auto space-y-10">
                    <!-- Upload Post Component -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow" x-data="{ previews: [], fileCount: 0 }">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border text-gray-400 flex items-center justify-center font-display text-xl">
                                        @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="font-bold text-gray-800 tracking-tight">Share a look</span>
                                </div>
                                <textarea name="caption" rows="2" placeholder="Write a caption for your portfolio shot or editorial..." class="w-full border-gray-200 focus:border-ink focus:ring-ink rounded-xl shadow-inner resize-none bg-gray-50 px-4 py-3 placeholder:text-gray-400"></textarea>
                            </div>

                            <!-- Image Previews Grid -->
                            <div x-show="previews.length > 0" x-cloak class="mb-4 grid grid-cols-3 gap-3">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                        <img :src="preview" class="w-full h-32 object-cover" />
                                        <button type="button" @click="previews.splice(index, 1); fileCount--;" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                                <div class="relative overflow-hidden inline-block border border-gray-200 rounded-lg bg-white px-4 py-2 hover:bg-gray-50 transition-colors shadow-sm cursor-pointer group">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-600 group-hover:text-ink">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span x-text="fileCount ? `${fileCount} image${fileCount > 1 ? 's' : ''} (max 3)` : 'Choose Images (Optional)'"></span>
                                    </div>
                                    <input type="file" name="images[]" accept="image/*" multiple x-ref="fileInput"
                                        @change="
                                            const files = $event.target.files;
                                            const maxFiles = 3;
                                            const currentCount = previews.length;
                                            let newCount = 0;
                                            for (let i = 0; i < files.length && currentCount + newCount < maxFiles; i++) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { previews.push(e.target.result); };
                                                reader.readAsDataURL(files[i]);
                                                newCount++;
                                            }
                                            fileCount = previews.length;
                                        "
                                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" />
                                </div>
                                <x-primary-button class="whitespace-nowrap w-full sm:w-auto justify-center rounded-lg px-6 py-3 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Post Original</x-primary-button>
                            </div>
                            <x-input-error :messages="$errors->get('error')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                            <x-input-error :messages="$errors->get('caption')" class="mt-2" />
                        </form>
                    </div>

                    <!-- Feed -->
                    <div class="space-y-12" id="posts-container">
                        @forelse($posts as $post)
                            <div class="bg-white border border-gray-100 text-ink rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 post-item">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-5">
                                    <a href="{{ route('profile.show', $post->user) }}" class="flex items-center gap-3 group flex-grow">
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 ring-2 ring-gray-100 group-hover:ring-gray-300 transition text-gray-400 flex items-center justify-center font-display text-xl">
                                            @if($post->user->profile && $post->user->profile->avatar)
                                                <img src="{{ asset('storage/' . $post->user->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($post->user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <h3 class="font-bold text-[15px] group-hover:underline text-gray-900 tracking-tight flex items-center">
                                                {{ $post->user->name }}
                                                @if($post->user->is_verified)
                                                    <x-verified-badge />
                                                @endif
                                            </h3>
                                            <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ $post->user->role->name }} &bull; {{ $post->created_at->shortAbsoluteDiffForHumans() }}</p>
                                        </div>
                                    </a>

                                    <!-- Delete Button (Owner Only) -->
                                    @if(Auth::id() === $post->user_id || Auth::user()->isAdmin())
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete post">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Post Images (Swipable) -->
                                @php
                                    $postImages = [];
                                    // Handle both old 'image' and new 'images' field
                                    if (!empty($post->images) && is_array($post->images)) {
                                        $postImages = $post->images;
                                    } elseif (!empty($post->image)) {
                                        // Fallback for old single image posts
                                        $postImages = [$post->image];
                                    }
                                    
                                    $postImages = array_map(function($img) {
                                        if (!empty($img)) {
                                            if (strpos($img, 'http') === 0) {
                                                return $img;
                                            }
                                            // If path doesn't start with 'storage/', prepend it
                                            if (strpos($img, 'storage/') !== 0) {
                                                return 'storage/' . $img;
                                            }
                                        }
                                        return $img;
                                    }, $postImages);
                                @endphp
                                
                                @if(!empty($postImages))
                                    <div class="w-full bg-gray-100 border-y border-gray-100 relative" x-data="{ current: 0, images: {{ json_encode($postImages) }}, getImageUrl(img) { return img.indexOf('http') === 0 ? img : '{{ asset('') }}' + img; } }">
                                        <!-- Main Image -->
                                        <img :src="getImageUrl(images[current])" :alt="'Post image ' + (current + 1)" class="w-full h-auto object-cover max-h-[850px] transition-opacity duration-300" loading="lazy" onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                                        
                                        <!-- Image Counter -->
                                        <template x-if="images.length > 1">
                                            <div class="absolute top-3 right-3 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                                            </div>
                                        </template>

                                        <!-- Navigation Arrows -->
                                        <template x-if="images.length > 1">
                                            <div>
                                                <button @click="current = current > 0 ? current - 1 : images.length - 1" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-black flex items-center justify-center transition-all shadow-lg hover:shadow-xl">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                </button>
                                                <button @click="current = current < images.length - 1 ? current + 1 : 0" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-black flex items-center justify-center transition-all shadow-lg hover:shadow-xl">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </button>
                                            </div>
                                        </template>

                                        <!-- Image Dots -->
                                        <template x-if="images.length > 1">
                                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                                                <template x-for="(image, idx) in images" :key="idx">
                                                    <button @click="current = idx" :class="idx === current ? 'bg-white' : 'bg-white/50'" class="w-2 h-2 rounded-full transition-all hover:bg-white"></button>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                @endif

                                @if(empty($postImages) && $post->caption)
                                    <!-- Caption-only Post (Status Style) -->
                                    <div class="px-5 py-12 text-center border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white">
                                        <p class="text-2xl text-gray-800 font-light leading-relaxed mb-6 whitespace-pre-wrap break-words">{!! nl2br(e($post->caption)) !!}</p>
                                        <p class="text-xs text-gray-400">by <span class="font-semibold text-gray-600">
                                            <a href="{{ route('profile.show', $post->user) }}" class="hover:underline">
                                                {{ $post->user->name }}
                                            </a>
                                        </span></p>
                                    </div>
                                @endif

                                <!-- Post Actions & Caption -->
                                <div class="p-5 space-y-4">
                                    <div class="flex items-center gap-6">
                                        @php
                                            $hasLiked = $post->likes->contains('user_id', Auth::id());
                                        @endphp
                                        <form action="{{ route('posts.like', $post) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="group flex items-center gap-2 {{ $hasLiked ? 'text-rose-500' : 'text-gray-500 hover:text-rose-400' }} transition-colors">
                                                <svg class="w-8 h-8 transform group-hover:scale-110 transition-transform" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="font-bold text-sm tracking-widest text-gray-700">{{ $post->likes->count() }}</span>
                                            </button>
                                        </form>
                                        <div class="flex items-center gap-2 text-gray-500 cursor-pointer">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span class="font-bold text-sm tracking-widest">{{ $post->comments->count() }}</span>
                                        </div>
                                    </div>

                                    @if(!empty($postImages) && $post->caption)
                                        <div class="text-sm mt-3 text-gray-800 leading-relaxed">
                                            <a href="{{ route('profile.show', $post->user) }}" class="font-bold hover:underline tracking-tight inline-flex items-center">
                                                {{ $post->user->name }}
                                                @if($post->user->is_verified)
                                                    <x-verified-badge />
                                                @endif
                                            </a>
                                            <span class="ml-1">{!! nl2br(e($post->caption)) !!}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Comments section -->
                                @if($post->comments->count() > 0)
                                    <div class="px-5 pb-4 space-y-3">
                                        @foreach($post->comments->take(3) as $comment)
                                            <div class="text-[13px] flex gap-2 leading-tight items-start group">
                                                <div class="flex-grow">
                                                    <a href="{{ route('profile.show', $comment->user) }}" class="font-bold hover:underline whitespace-nowrap text-gray-900 inline-flex items-center">
                                                        {{ $comment->user->name }}
                                                        @if($comment->user->is_verified)
                                                            <x-verified-badge />
                                                        @endif
                                                    </a>
                                                    <span class="text-gray-700">{{ $comment->body }}</span>
                                                </div>
                                                @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100" title="Delete comment">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($post->comments->count() > 3)
                                            <a href="{{ route('posts.show', $post) }}" class="text-[13px] text-gray-400 font-semibold hover:text-gray-600 block mt-1">View all {{ $post->comments->count() }} comments</a>
                                        @endif
                                    </div>
                                @endif

                                <!-- Add Comment -->
                                <form action="{{ route('posts.comment', $post) }}" method="POST" class="border-t border-gray-100 flex items-center bg-gray-50/50">
                                    @csrf
                                    <input type="text" name="body" placeholder="Add a comment..." required class="flex-grow border-0 focus:ring-0 text-sm py-4 px-5 bg-transparent placeholder:text-gray-400">
                                    <button type="submit" class="font-bold text-xs shadow-sm bg-white border border-gray-200 rounded-md py-1.5 px-3 mr-4 uppercase tracking-widest text-ink hover:text-indigo-600 transition-colors">Post</button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-24 text-gray-500 flex flex-col items-center justify-center border border-dashed border-gray-300 rounded-2xl bg-white shadow-sm">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xl font-display tracking-widest uppercase mb-2 text-gray-400">The feed is empty</p>
                                <p class="text-sm">Be the first to share your portfolio and looks with the community.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Lazy Load Button -->
                    <div class="text-center pb-12">
                        <button id="load-more-btn" class="px-8 py-3 bg-ink text-white font-bold uppercase tracking-widest rounded-lg hover:bg-gray-800 transition-colors shadow-md" data-page="2">
                            Load More Posts
                        </button>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR: Applied Gigs (desktop only) -->
                <div class="hidden lg:block w-72 shrink-0 space-y-6 sticky top-24 self-start">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-400">My Applications</h4>
                            <a href="{{ route('job_applications.mine') }}" class="text-[10px] font-bold uppercase tracking-widest text-ink hover:underline">View All</a>
                        </div>
                        
                        @if($myApplications->count() > 0)
                            <div class="divide-y divide-gray-50">
                                @foreach($myApplications as $app)
                                    <a href="{{ route('jobs.show', $app->job) }}" class="block px-5 py-3.5 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-sm font-semibold text-ink truncate">{{ $app->job->title }}</p>
                                            @if($app->status === 'pending')
                                                <span class="w-2 h-2 rounded-full bg-yellow-400 shrink-0" title="Under Review"></span>
                                            @elseif($app->status === 'accepted')
                                                <span class="w-2 h-2 rounded-full bg-green-500 shrink-0" title="Accepted"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-red-400 shrink-0" title="Not Selected"></span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                                                {{ $app->status === 'pending' ? 'Under Review' : ($app->status === 'accepted' ? 'Accepted' : 'Not Selected') }}
                                            </span>
                                            <span class="text-[10px] text-gray-300">&bull;</span>
                                            <span class="text-[10px] text-gray-400">{{ $app->created_at->shortAbsoluteDiffForHumans() }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <p class="text-xs text-gray-400">No applications yet.</p>
                                <a href="{{ route('jobs.index') }}" class="text-xs text-ink font-bold hover:underline mt-1 inline-block">Browse Gigs &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lazy Loading
     

        function createPostHTML(post) {
            // Ensure images array exists and handle both old 'image' and new 'images' field
            let postImages = [];
            if (post.images && Array.isArray(post.images) && post.images.length > 0) {
                postImages = post.images;
            } else if (post.image) {
                postImages = [post.image];
            }

            // Only add 'storage/' prefix to local file paths, not Cloudinary URLs
            postImages = postImages.map(img => {
                if (img) {
                    // Check if it's a Cloudinary URL or other external URL (starts with http)
                    if (img.indexOf('http') === 0) {
                        return img;
                    }
                    // Add storage prefix for local files
                    if (img.indexOf('storage/') !== 0) {
                        return 'storage/' + img;
                    }
                }
                return img;
            });

            // Parse the created_at date to relative format
            const createdDate = new Date(post.created_at);
            const now = new Date();
            const diffMs = now - createdDate;
            const diffSecs = Math.floor(diffMs / 1000);
            const diffMins = Math.floor(diffSecs / 60);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);
            
            let timeAgo = '';
            if (diffMins < 1) timeAgo = 'now';
            else if (diffMins < 60) timeAgo = diffMins + 'm';
            else if (diffHours < 24) timeAgo = diffHours + 'h';
            else if (diffDays < 7) timeAgo = diffDays + 'd';
            else timeAgo = createdDate.toLocaleDateString();

            // Helper function to get proper image URL
            const getImageUrl = (imgPath) => {
                if (imgPath.indexOf('http') === 0) {
                    return imgPath; // Already a full URL (Cloudinary)
                }
                return '{{ asset('') }}' + imgPath; // Local file
            };

            // Construct image carousel HTML
            let imagesHTML = '';
            if (postImages.length > 0) {
                imagesHTML = `
                    <div class="w-full bg-gray-100 border-y border-gray-100 relative carousel-container" data-images='${JSON.stringify(postImages)}'>
                        <img src="${getImageUrl(postImages[0])}" alt="Post image" class="w-full h-auto object-cover max-h-[850px]" onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                `;
                
                if (postImages.length > 1) {
                    // Multiple images - add carousel controls
                    imagesHTML += `
                        <div class="absolute top-3 right-3 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            <span class="current-img">1</span> / <span>${postImages.length}</span>
                        </div>
                        <button type="button" class="prev-img absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-black flex items-center justify-center transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button type="button" class="next-img absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-black flex items-center justify-center transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            ${postImages.map((_, idx) => `<button type="button" class="dot-indicator w-2 h-2 rounded-full transition-all ${idx === 0 ? 'bg-white' : 'bg-white/50'}" data-idx="${idx}"></button>`).join('')}
                        </div>
                    `;
                }
                
                imagesHTML += `</div>`;
            }

            // Build user info section
            const userAvatarUrl = post.user && post.user.profile && post.user.profile.avatar 
                ? '{{ asset("storage") }}/' + post.user.profile.avatar 
                : null;
            const userInitial = post.user ? post.user.name.charAt(0) : '?';
            const userRole = post.user && post.user.role ? post.user.role.name : 'User';
            
            // Build verified badge HTML
            const verifiedBadge = post.user && post.user.is_verified 
                ? `<div class="ml-1 w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-sm" title="Verified Member"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>`
                : '';

            // Count likes and comments
            const likesCount = post.likes ? post.likes.length : 0;
            const commentsCount = post.comments ? post.comments.length : 0;

            return `
                <div class="post-item bg-white border border-gray-100 text-ink rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <!-- Post Header -->
                    <div class="flex items-center justify-between p-5">
                        <a href="/u/${post.user ? post.user.id : '#'}" class="flex items-center gap-3 group flex-grow">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 ring-2 ring-gray-100 group-hover:ring-gray-300 transition text-gray-400 flex items-center justify-center font-display text-xl">
                                ${userAvatarUrl ? `<img src="${userAvatarUrl}" class="w-full h-full object-cover">` : userInitial}
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-bold text-[15px] group-hover:underline text-gray-900 tracking-tight flex items-center">
                                    ${post.user ? post.user.name : 'Unknown'} ${verifiedBadge}
                                </h3>
                                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">${userRole} &bull; ${timeAgo}</p>
                            </div>
                        </a>
                    </div>

                    <!-- Post Images -->
                    ${imagesHTML}

                    <!-- Post Caption/Content (Appears before interactions for caption-only) -->
                    ${post.caption && postImages.length === 0 ? `
                        <!-- Caption-only Post (Status Style) -->
                        <div class="px-5 py-12 text-center border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white">
                            <p class="text-2xl text-gray-800 font-light leading-relaxed mb-6 whitespace-pre-wrap break-words">${post.caption}</p>
                            <p class="text-xs text-gray-400">by <span class="font-semibold text-gray-600">${post.user ? post.user.name : 'Unknown'}</span></p>
                        </div>
                    ` : ''}

                    <!-- Post Interactions -->
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between text-[13px]">
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                            <span>${likesCount} likes</span>
                        </div>
                        <div class="flex items-center gap-4 text-gray-500">
                            <span>${commentsCount} comments</span>
                        </div>
                    </div>

                    <!-- Post Actions -->
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-4">
                        <form action="/posts/${post.id}/like" method="POST" class="flex-1">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 text-gray-600 hover:text-ink font-semibold text-sm py-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                Like
                            </button>
                        </form>
                        <a href="/posts/${post.id}" class="flex-1 flex items-center justify-center gap-2 text-gray-600 hover:text-ink font-semibold text-sm py-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Comment
                        </a>
                    </div>

                    <!-- Post Caption (Appears after interactions for posts with images) -->
                    ${post.caption && postImages.length > 0 ? `
                        <!-- Caption with Images -->
                        <div class="px-5 pt-4 pb-2">
                            <p class="text-sm text-gray-800"><span class="font-bold">${post.user ? post.user.name : 'Unknown'}</span> ${post.caption}</p>
                        </div>
                    ` : ''}

                    <!-- View Comments Link -->
                    ${commentsCount > 0 ? `
                        <div class="px-5 py-2 border-t border-gray-100">
                            <a href="/posts/${post.id}" class="text-[13px] text-gray-400 font-semibold hover:text-gray-600">View all ${commentsCount} comments →</a>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Add carousel handlers for dynamically loaded posts
        function attachCarouselHandlers(postElement) {
            const carousel = postElement.querySelector('.carousel-container');
            if (!carousel) return;

            const images = carousel.dataset.images ? JSON.parse(carousel.dataset.images) : [];
            if (images.length <= 1) return;

            let currentIdx = 0;
            const img = carousel.querySelector('img');
            const prevBtn = carousel.querySelector('.prev-img');
            const nextBtn = carousel.querySelector('.next-img');
            const counter = carousel.querySelector('.current-img');
            const dots = carousel.querySelectorAll('.dot-indicator');

            const updateCarousel = () => {
                const imgUrl = images[currentIdx].indexOf('http') === 0 
                    ? images[currentIdx] 
                    : '{{ asset('') }}' + images[currentIdx];
                img.src = imgUrl;
                img.onerror = () => img.src = '{{ asset('images/placeholder.svg') }}';
                if (counter) counter.textContent = currentIdx + 1;
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('bg-white', idx === currentIdx);
                    dot.classList.toggle('bg-white/50', idx !== currentIdx);
                });
            };

            prevBtn?.addEventListener('click', () => {
                currentIdx = currentIdx > 0 ? currentIdx - 1 : images.length - 1;
                updateCarousel();
            });

            nextBtn?.addEventListener('click', () => {
                currentIdx = currentIdx < images.length - 1 ? currentIdx + 1 : 0;
                updateCarousel();
            });

            dots.forEach((dot, idx) => {
                dot.addEventListener('click', () => {
                    currentIdx = idx;
                    updateCarousel();
                });
            });
        }

        // Infinite Scroll Lazy Loading
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-btn');
            const postsContainer = document.getElementById('posts-container');
            let isLoading = false;

            if (!loadMoreBtn) return;

            const loadMorePosts = async () => {
                if (isLoading || loadMoreBtn.style.display === 'none') return;
                
                isLoading = true;
                const currentPage = parseInt(loadMoreBtn.getAttribute('data-page'));
                loadMoreBtn.innerHTML = '<span class="animate-pulse">Loading...</span>';

                try {
                    const response = await fetch(`{{ route('posts.api') }}?page=${currentPage}`);
                    const result = await response.json();

                    // Append new posts
                    result.data.forEach(post => {
                        const postHTML = createPostHTML(post);
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = postHTML;
                        const postElement = tempDiv.firstElementChild;
                        postsContainer.appendChild(postElement);
                        
                        // Attach carousel and interaction handlers
                        attachCarouselHandlers(postElement);
                    });

                    // Update page number
                    loadMoreBtn.setAttribute('data-page', currentPage + 1);
                    loadMoreBtn.innerHTML = 'Load More Posts';

                    // Hide button if no more posts
                    if (!result.has_more) {
                        loadMoreBtn.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Failed to load posts:', error);
                    loadMoreBtn.innerHTML = 'Error Loading. Try Again.';
                } finally {
                    isLoading = false;
                }
            };

            // Setup Manual Click
            loadMoreBtn.addEventListener('click', loadMorePosts);
        });
    </script>
</x-app-layout>

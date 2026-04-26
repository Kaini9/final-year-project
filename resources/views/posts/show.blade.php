<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-ink font-bold text-sm uppercase tracking-widest mb-6 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Feed
            </a>

            <!-- Post Card -->
            <div class="bg-white border border-gray-100 text-ink rounded-2xl overflow-hidden shadow-lg post-item">
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
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Post Images -->
                @php
                    $postImages = [];
                    if (!empty($post->images) && is_array($post->images)) {
                        $postImages = $post->images;
                    } elseif (!empty($post->image)) {
                        $postImages = [$post->image];
                    }
                    
                    // Only add 'storage/' prefix to local file paths, not Cloudinary URLs
                    $postImages = array_map(function($img) {
                        if (!empty($img)) {
                            // Check if it's a Cloudinary URL (starts with http)
                            if (strpos($img, 'http') === 0) {
                                return $img;
                            }
                            // Add storage prefix for local files
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
                        <img :src="getImageUrl(images[current])" :alt="'Post image ' + (current + 1)" class="w-full h-auto object-cover max-h-[600px] transition-opacity duration-300" loading="lazy" onerror="this.src='{{ asset('images/placeholder.svg') }}'" />
                        
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

                <!-- Post Actions & Caption -->
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-6">
                        @php
                            $hasLiked = $post->likes->contains('user_id', Auth::id());
                        @endphp
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <button type="submit" class="group flex items-center gap-2 {{ $hasLiked ? 'text-rose-500' : 'text-gray-500 hover:text-rose-400' }} transition-colors">
                                <svg class="w-8 h-8 transform group-hover:scale-110 transition-transform" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="font-bold text-sm tracking-widest text-gray-700">{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                        <div class="flex items-center gap-2 text-gray-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="font-bold text-sm tracking-widest">{{ $post->comments->count() }}</span>
                        </div>
                    </div>

                    @if($post->caption)
                        @if(empty($postImages))
                            <!-- Caption-only Post (Status Style) -->
                            <div class="px-5 py-6 border-t border-gray-100">
                                <p class="text-2xl text-gray-800 font-light leading-relaxed whitespace-pre-wrap break-words">{!! nl2br(e($post->caption)) !!}</p>
                            </div>
                        @else
                            <!-- Caption with Images -->
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
                    @endif
                </div>

                <!-- Comments Section -->
                <div class="border-t border-gray-100 p-5 space-y-4">
                    <h3 class="font-bold text-ink text-sm uppercase tracking-widest">{{ $post->comments->count() }} Comments</h3>

                    <!-- Add Comment Form -->
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="flex gap-3 pb-4 border-b border-gray-100">
                        @csrf
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center font-display text-xs text-gray-400 shrink-0">
                            @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="flex-grow flex gap-2">
                            <input type="text" name="body" placeholder="Add a comment..." 
                                class="flex-grow border border-gray-200 rounded-full px-4 py-2 text-sm focus:ring-ink focus:border-ink bg-gray-50 placeholder:text-gray-400"
                                required />
                            <button type="submit" class="px-4 py-2 bg-ink text-white rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors">
                                Post
                            </button>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="space-y-4">
                        @forelse($post->comments as $comment)
                            <div class="flex gap-3 group">
                                <a href="{{ route('profile.show', $comment->user) }}" class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center font-display text-xs text-gray-400 shrink-0 hover:ring-2 hover:ring-gray-300 transition">
                                    @if($comment->user->profile && $comment->user->profile->avatar)
                                        <img src="{{ asset('storage/' . $comment->user->profile->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($comment->user->name, 0, 1) }}
                                    @endif
                                </a>
                                <div class="flex-grow">
                                    <div class="bg-gray-50 rounded-lg px-3 py-2">
                                        <a href="{{ route('profile.show', $comment->user) }}" class="font-bold text-sm text-ink hover:underline inline-flex items-center gap-1">
                                            {{ $comment->user->name }}
                                            @if($comment->user->is_verified)
                                                <x-verified-badge />
                                            @endif
                                        </a>
                                        <p class="text-sm text-gray-700 mt-1">{{ $comment->body }}</p>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 font-semibold">
                                        <span>{{ $comment->created_at->shortAbsoluteDiffForHumans() }}</span>
                                        @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors underline">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 text-sm py-4">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

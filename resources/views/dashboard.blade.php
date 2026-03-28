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
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow" x-data="{ preview: null, fileName: '' }">
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

                            <!-- Image Preview -->
                            <div x-show="preview" x-cloak class="mb-4 relative rounded-xl overflow-hidden border border-gray-200 bg-gray-100">
                                <img :src="preview" class="w-full max-h-72 object-cover" />
                                <button type="button" @click="preview = null; fileName = ''; $refs.fileInput.value = ''" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                                <div class="relative overflow-hidden inline-block border border-gray-200 rounded-lg bg-white px-4 py-2 hover:bg-gray-50 transition-colors shadow-sm cursor-pointer group">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-600 group-hover:text-ink">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span x-text="fileName || 'Choose Image'"></span>
                                    </div>
                                    <input type="file" name="image" accept="image/*" required x-ref="fileInput"
                                        @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                fileName = file.name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => { preview = e.target.result; };
                                                reader.readAsDataURL(file);
                                            }
                                        "
                                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" />
                                </div>
                                <x-primary-button class="whitespace-nowrap w-full sm:w-auto justify-center rounded-lg px-6 py-3 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Post Original</x-primary-button>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            <x-input-error :messages="$errors->get('caption')" class="mt-2" />
                        </form>
                    </div>

                    <!-- Feed -->
                    <div class="space-y-12">
                        @forelse($posts as $post)
                            <div class="bg-white border border-gray-100 text-ink rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-5">
                                    <a href="{{ route('profile.show', $post->user) }}" class="flex items-center gap-3 group">
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
                                                @if($post->user->verification && $post->user->verification->status === 'approved')
                                                    <x-verified-badge />
                                                @endif
                                            </h3>
                                            <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ $post->user->role->name }} &bull; {{ $post->created_at->shortAbsoluteDiffForHumans() }}</p>
                                        </div>
                                    </a>
                                    @if(Auth::id() === $post->user_id || Auth::user()->isAdmin())
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Post Image -->
                                <div class="w-full bg-gray-100 border-y border-gray-100">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full h-auto object-cover max-h-[850px]" loading="lazy">
                                </div>

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

                                    @if($post->caption)
                                        <div class="text-sm mt-3 text-gray-800 leading-relaxed">
                                            <a href="{{ route('profile.show', $post->user) }}" class="font-bold hover:underline tracking-tight inline-flex items-center">
                                                {{ $post->user->name }}
                                                @if($post->user->verification && $post->user->verification->status === 'approved')
                                                    <x-verified-badge />
                                                @endif
                                            </a>
                                            <span class="ml-1">{!! nl2br(e($post->caption)) !!}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Comments section -->
                                @if($post->comments->count() > 0)
                                    <div class="px-5 pb-4 space-y-2">
                                        @foreach($post->comments->take(3) as $comment)
                                            <div class="text-[13px] flex gap-2 leading-tight">
                                                <a href="{{ route('profile.show', $comment->user) }}" class="font-bold hover:underline whitespace-nowrap text-gray-900 inline-flex items-center">
                                                    {{ $comment->user->name }}
                                                    @if($comment->user->verification && $comment->user->verification->status === 'approved')
                                                        <x-verified-badge />
                                                    @endif
                                                </a>
                                                <span class="text-gray-700">{{ $comment->body }}</span>
                                            </div>
                                        @endforeach
                                        @if($post->comments->count() > 3)
                                            <a href="#" class="text-[13px] text-gray-400 font-semibold hover:text-gray-600 block mt-1">View all {{ $post->comments->count() }} comments</a>
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

                    <div class="pt-8 pb-12">
                        {{ $posts->links() }}
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

                    <!-- Legend -->
                   
            </div>
        </div>
    </div>
</x-app-layout>

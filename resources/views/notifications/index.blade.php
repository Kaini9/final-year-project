<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-center justify-between mb-8">
                <h1 class="font-display text-4xl uppercase tracking-widest text-ink">Notifications</h1>
                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-ink transition-colors">Mark All Read</button>
                    </form>
                @endif
            </div>

            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 bg-white border border-green-200" role="alert">
                    <span class="font-medium">Success!</span> {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border text-ink rounded-2xl overflow-hidden shadow-sm">
                @forelse($notifications as $notification)
                    <div class="border-b border-gray-100 last:border-0 {{ is_null($notification->read_at) ? 'bg-indigo-50/30' : '' }} transition-colors relative group">
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ is_null($notification->read_at) ? 'bg-indigo-500' : 'bg-transparent' }}"></div>
                        
                        <div class="p-5 sm:p-6 flex items-start gap-4">
                            <!-- Icon/Avatar based on type -->
                            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-100 flex items-center justify-center text-gray-400 bg-white font-display text-xl {{ is_null($notification->read_at) ? 'ring-2 ring-indigo-100' : '' }}">
                                @if($notification->data['type'] === 'like')
                                    <svg class="w-6 h-6 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                @elseif($notification->data['type'] === 'comment')
                                    <svg class="w-6 h-6 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                @elseif($notification->data['type'] === 'follow')
                                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                @elseif($notification->data['type'] === 'application')
                                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                @elseif($notification->data['type'] === 'application_status')
                                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($notification->data['type'] === 'message')
                                    <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>

                            <div class="flex-grow">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-700 transition-colors {{ is_null($notification->read_at) ? 'font-bold' : '' }}">
                                    <!-- Wrap message in form to mark read and redirect -->
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-left w-full hover:underline decoration-2 underline-offset-2">
                                            {{ $notification->data['message'] }}
                                        </button>
                                    </form>
                                </p>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>

                            @if(is_null($notification->read_at))
                                <div class="shrink-0">
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-2.5 h-2.5 rounded-full bg-indigo-500 flex items-center justify-center ring-4 ring-white" title="Mark as read"></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-24 text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <p class="text-xl font-display tracking-widest uppercase text-gray-400 mb-2">You're all caught up</p>
                        <p class="text-sm text-gray-500 max-w-sm">When creatives interact with your portfolio and gigs, you'll be notified here.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 pb-12">
                {{ $notifications->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>

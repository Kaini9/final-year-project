<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="flex" style="height: calc(100vh - 65px);">
                
                <!-- LEFT: Conversation List Sidebar -->
                <div class="w-96 shrink-0 bg-white border-r border-gray-200 flex flex-col">
                    <!-- Sidebar Header -->
                    <div class="p-5 border-b border-gray-100 shrink-0">
                        <h1 class="font-display text-2xl uppercase tracking-widest text-ink">Messages</h1>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-100 shrink-0">
                        <button onclick="toggleTab('inbox')" id="inbox-tab" 
                            class="flex-1 py-3 text-sm font-bold uppercase tracking-widest border-b-2 border-ink text-ink cursor-pointer transition-colors">
                            Inbox
                            @if($regularConversations->count() > 0)
                                <span class="inline-block ml-2 bg-ink text-white text-[10px] px-2 py-0.5 rounded-full">{{ $regularConversations->count() }}</span>
                            @endif
                        </button>
                        <button onclick="toggleTab('spam')" id="spam-tab" 
                            class="flex-1 py-3 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 cursor-pointer transition-colors hover:text-ink">
                            Message Requests
                            @if($spamConversations->count() > 0)
                                <span class="inline-block ml-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $spamConversations->count() }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- Conversation List -->
                    <div class="flex-grow overflow-y-auto">
                        <!-- Inbox Tab -->
                        <div id="inbox-content">
                            @if($regularConversations->count() > 0)
                                @foreach($regularConversations as $convo)
                                    <a href="{{ route('messages.show', $convo->partner) }}" 
                                       class="flex items-center gap-3 px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ $activeUser && $activeUser->id === $convo->partner->id ? 'bg-gray-100 border-l-4 border-l-ink' : '' }}">
                                        
                                        <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden bg-gray-200 border relative">
                                            @if($convo->partner->profile && $convo->partner->profile->avatar)
                                                <img src="{{ asset('storage/' . $convo->partner->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-500 font-display text-lg">{{ substr($convo->partner->name, 0, 1) }}</div>
                                            @endif
                                            @if($convo->unread_count > 0)
                                                <span class="absolute -top-0.5 -right-0.5 block w-3 h-3 rounded-full bg-red-500 ring-2 ring-white"></span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-grow min-w-0">
                                            <div class="flex justify-between items-center">
                                                <h3 class="font-bold text-sm text-ink truncate">{{ $convo->partner->name }}</h3>
                                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest shrink-0 ml-2">{{ $convo->latest_message->created_at->shortAbsoluteDiffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 truncate mt-0.5 {{ $convo->unread_count > 0 ? 'font-bold text-ink' : '' }}">
                                                @if($convo->latest_message->sender_id === Auth::id())
                                                    <span class="text-gray-400">You: </span>
                                                @endif
                                                {{ $convo->latest_message->body }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-48 p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No conversations yet</p>
                                </div>
                            @endif
                        </div>

                        <!-- Message Requests (Spam) Tab -->
                        <div id="spam-content" class="hidden">
                            @if($spamConversations->count() > 0)
                                @foreach($spamConversations as $convo)
                                    <div class="px-5 py-4 border-b border-gray-50 bg-orange-50/50 hover:bg-orange-50/80 transition-colors {{ $activeUser && $activeUser->id === $convo->partner->id ? 'bg-orange-100/50 border-l-4 border-l-orange-500' : '' }}">
                                        <a href="{{ route('messages.show', $convo->partner) }}" 
                                           class="flex items-center gap-3">
                                            
                                            <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden bg-gray-200 border relative">
                                                @if($convo->partner->profile && $convo->partner->profile->avatar)
                                                    <img src="{{ asset('storage/' . $convo->partner->profile->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-500 font-display text-lg">{{ substr($convo->partner->name, 0, 1) }}</div>
                                                @endif
                                                @if($convo->unread_count > 0)
                                                    <span class="absolute -top-0.5 -right-0.5 block w-3 h-3 rounded-full bg-red-500 ring-2 ring-white"></span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-grow min-w-0">
                                                <div class="flex justify-between items-center">
                                                    <h3 class="font-bold text-sm text-ink truncate">{{ $convo->partner->name }}</h3>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest shrink-0 ml-2">{{ $convo->latest_message->created_at->shortAbsoluteDiffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 truncate mt-0.5 {{ $convo->unread_count > 0 ? 'font-bold text-ink' : '' }}">
                                                    @if($convo->latest_message->sender_id === Auth::id())
                                                        <span class="text-gray-400">You: </span>
                                                    @endif
                                                    {{ $convo->latest_message->body }}
                                                </p>
                                            </div>
                                        </a>

                                        <!-- Accept Message Request Button -->
                                        <form action="{{ route('messages.accept-spam', $convo->partner) }}" method="POST" class="mt-2">
                                            @csrf
                                            <button type="submit" class="w-full bg-ink text-white text-xs font-bold py-1.5 rounded-lg hover:bg-opacity-90 transition-all">
                                                ✓ Accept Message Request
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-48 p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No message requests</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Chat Panel -->
                <div class="flex-grow flex flex-col bg-gray-50">
                    @if($activeUser)
                        <!-- Chat Header -->
                        <div class="bg-ink p-4 flex items-center gap-4 shrink-0 shadow-md z-10">
                            <a href="{{ route('profile.show', $activeUser) }}" class="shrink-0">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-800 ring-2 ring-gray-600 hover:ring-white transition-all">
                                    @if($activeUser->profile && $activeUser->profile->avatar)
                                        <img src="{{ asset('storage/' . $activeUser->profile->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-display text-lg">{{ substr($activeUser->name, 0, 1) }}</div>
                                    @endif
                                </div>
                            </a>
                            <div class="flex-grow">
                                <a href="{{ route('profile.show', $activeUser) }}" class="font-bold text-white hover:underline tracking-tight">{{ $activeUser->name }}</a>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $activeUser->role->name }}</p>
                            </div>
                        </div>

                        <!-- Messages Thread -->
                        <div class="flex-grow px-6 py-5 overflow-y-auto space-y-3" id="message-container">
                            @forelse($messages as $index => $msg)
                                @php
                                    $isOwn = $msg->sender_id === Auth::id();
                                    $showDate = $index === 0 || !$messages[$index - 1]->created_at->isSameDay($msg->created_at);
                                @endphp

                                @if($showDate)
                                    <div class="flex items-center justify-center my-3">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-white border border-gray-100 rounded-full px-3 py-0.5">{{ $msg->created_at->format('M j, Y') }}</span>
                                    </div>
                                @endif

                                <div class="flex items-end gap-2 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                    @if(!$isOwn)
                                        <div class="w-7 h-7 shrink-0 rounded-full overflow-hidden bg-gray-200 border mb-4">
                                            @if($activeUser->profile && $activeUser->profile->avatar)
                                                <img src="{{ asset('storage/' . $activeUser->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-500 font-display text-[10px]">{{ substr($activeUser->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="inline-block {{ $isOwn ? 'bg-ink text-white rounded-2xl rounded-br-sm' : 'bg-white text-ink border border-gray-200 rounded-2xl rounded-bl-sm' }} px-4 py-2.5 shadow-sm text-sm leading-relaxed" style="max-width: 65%;">
                                        {{ $msg->body }}
                                        <span class="block text-right mt-1 {{ $isOwn ? 'text-gray-400' : 'text-gray-400' }}" style="font-size: 9px;">
                                            {{ $msg->created_at->format('g:i A') }}
                                            @if($isOwn && $msg->read_at)
                                                <svg class="w-3 h-3 inline-block ml-0.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </span>
                                    </div>

                                    @if($isOwn)
                                        <div class="w-7 h-7 shrink-0 rounded-full overflow-hidden bg-gray-200 border mb-4">
                                            @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                                <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-500 font-display text-[10px]">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-center p-8">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <p class="text-sm text-gray-500">Send a message to start networking with <span class="font-bold text-ink">{{ $activeUser->name }}</span></p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Input Box -->
                        <div class="bg-white p-4 border-t border-gray-100 shrink-0">
                            <form action="{{ route('messages.store', $activeUser) }}" method="POST" class="flex items-end gap-3">
                                @csrf
                                <textarea name="body" rows="1" placeholder="Type a message..." required
                                    class="flex-grow border border-gray-200 rounded-xl focus:ring-ink focus:border-ink resize-none text-sm py-3 px-4 bg-gray-50/50 focus:bg-white transition-colors"
                                    onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.closest('form').submit(); }"></textarea>
                                <button type="submit" class="shrink-0 w-11 h-11 rounded-xl bg-ink text-white flex items-center justify-center shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- No Chat Selected State -->
                        <div class="flex-grow flex flex-col items-center justify-center text-center p-12">
                            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <h3 class="font-display text-2xl uppercase tracking-widest text-gray-400 mb-2">Select a Chat</h3>
                            <p class="text-sm text-gray-500 max-w-xs">Choose a conversation from the left to start messaging, or visit a creative's profile to start a new one.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleTab(tab) {
            // Hide all tabs
            document.getElementById('inbox-content').classList.add('hidden');
            document.getElementById('spam-content').classList.add('hidden');
            
            // Remove active styles from all tabs
            document.getElementById('inbox-tab').classList.remove('border-ink', 'text-ink');
            document.getElementById('inbox-tab').classList.add('border-transparent', 'text-gray-400');
            document.getElementById('spam-tab').classList.remove('border-ink', 'text-ink');
            document.getElementById('spam-tab').classList.add('border-transparent', 'text-gray-400');
            
            // Show selected tab
            if(tab === 'inbox') {
                document.getElementById('inbox-content').classList.remove('hidden');
                document.getElementById('inbox-tab').classList.add('border-ink', 'text-ink');
                document.getElementById('inbox-tab').classList.remove('border-transparent', 'text-gray-400');
            } else {
                document.getElementById('spam-content').classList.remove('hidden');
                document.getElementById('spam-tab').classList.add('border-ink', 'text-ink');
                document.getElementById('spam-tab').classList.remove('border-transparent', 'text-gray-400');
            }
        }

        @if($activeUser)
            document.addEventListener('DOMContentLoaded', function() {
                var container = document.getElementById('message-container');
                container.scrollTop = container.scrollHeight;
            });
        @endif
    </script>
</x-app-layout>

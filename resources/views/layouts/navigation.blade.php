<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Feed') }}
                    </x-nav-link>
                    <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                        {{ __('Marketplace') }}
                    </x-nav-link>
                    <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        {{ __('Inbox') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side: Notifications & Profile -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-2">
                
                <!-- Notification Bell Dropdown -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative p-2 text-gray-400 hover:text-ink hover:bg-gray-50 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 flex items-center justify-center h-4 min-w-[16px] px-1 rounded-full bg-rose-500 text-white text-[9px] font-bold ring-2 ring-white">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <span class="font-bold text-ink text-sm">Notifications</span>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ Auth::user()->unreadNotifications->count() }} new</span>
                            @endif
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto w-80">
                            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-indigo-50/50' : '' }}">
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="block w-full text-left">
                                        @csrf
                                        <button type="submit" class="w-full text-left">
                                            <p class="text-xs text-gray-800 {{ is_null($notification->read_at) ? 'font-bold' : '' }} line-clamp-2">
                                                {{ $notification->data['message'] }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-gray-500 text-xs">
                                    No new notifications
                                </div>
                            @endforelse
                        </div>

                        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-ink hover:underline uppercase tracking-widest transition-all">View All</a>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- Profile Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col items-end mr-1 text-right">
                                <div class="font-bold text-ink">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-0.5">{{ Auth::user()->role->name }}</div>
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::user()->role->name === 'Admin')
                            <x-dropdown-link :href="route('admin.dashboard')" class="text-indigo-600 font-bold bg-indigo-50/50">
                                {{ __('Admin Dashboard') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Setup') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('job_applications.mine')">
                            {{ __('My Applications') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.settings')">
                            {{ __('Account Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                {{ __('Inbox') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                <div class="flex items-center justify-between">
                    <span>{{ __('Notifications') }}</span>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </div>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs font-bold uppercase tracking-widest mt-1 text-gray-500">{{ Auth::user()->role->name }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Setup') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('job_applications.mine')">
                    {{ __('My Applications') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.settings')">
                    {{ __('Account Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

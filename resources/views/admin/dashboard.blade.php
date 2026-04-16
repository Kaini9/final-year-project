<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-1">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500 font-medium">Platform overview and essential metrics.</p>
                </div>
                <div class="flex items-center gap-4 flex-wrap">
                    <a href="{{ route('admin.users') }}" class="bg-white border text-ink uppercase tracking-widest text-xs font-bold px-6 py-3 rounded-none hover:bg-gray-50 transition-colors shadow-sm">
                        Users
                    </a>
                    <a href="{{ route('admin.roles') }}" class="bg-white border text-ink uppercase tracking-widest text-xs font-bold px-6 py-3 rounded-none hover:bg-gray-50 transition-colors shadow-sm">
                        Roles
                    </a>
                    <a href="{{ route('admin.posts') }}" class="bg-white border text-ink uppercase tracking-widest text-xs font-bold px-6 py-3 rounded-none hover:bg-gray-50 transition-colors shadow-sm">
                        Posts
                    </a>
                    <a href="{{ route('admin.jobs') }}" class="bg-white border text-ink uppercase tracking-widest text-xs font-bold px-6 py-3 rounded-none hover:bg-gray-50 transition-colors shadow-sm">
                        Jobs
                    </a>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total Users -->
                <div class="bg-white p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-500">Total Users</h3>
                        </div>
                        <p class="font-display text-4xl text-ink">{{ number_format($metrics['total_users']) }}</p>
                    </div>
                </div>

                <!-- Total Posts -->
                <div class="bg-white p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-rose-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-500">Feed Posts</h3>
                        </div>
                        <p class="font-display text-4xl text-ink">{{ number_format($metrics['total_posts']) }}</p>
                    </div>
                </div>

                <!-- Active Gigs -->
                <div class="bg-white p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-500">Active Gigs</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="font-display text-4xl text-ink">{{ number_format($metrics['active_jobs']) }}</p>
                            <span class="text-sm font-medium text-gray-400">/ {{ number_format($metrics['total_jobs']) }} total</span>
                        </div>
                    </div>
                </div>

                <!-- Job Applications -->
                <div class="bg-white p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xs font-bold tracking-widest uppercase text-gray-500">Gig Pitches</h3>
                        </div>
                        <p class="font-display text-4xl text-ink">{{ number_format($metrics['total_applications']) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Verification System -->
            <div class="bg-white border shadow-sm p-8 flex flex-col md:flex-row items-center justify-between gap-6 rounded-2xl relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-50/50 to-transparent"></div>
                <div class="relative flex items-center gap-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-display text-2xl uppercase tracking-widest text-ink mb-1">Verification Requests</h3>
                        <p class="text-sm text-gray-500">
                            @if($metrics['pending_verifications'] > 0)
                                You have <strong class="text-indigo-600">{{ $metrics['pending_verifications'] }} pending setup requests</strong> waiting for review.
                            @else
                                All caught up! There are no pending verification requests to review.
                            @endif
                        </p>
                    </div>
                </div>
                <div class="relative shrink-0 w-full md:w-auto mt-4 md:mt-0">
                    <a href="{{ route('admin.verifications') }}" class="block text-center w-full px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded transition-colors shadow-sm">
                        Review Applications
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

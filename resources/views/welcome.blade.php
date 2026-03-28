<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FashionConnect — The Professional Network for Fashion</title>
    <meta name="description" content="Where designers, models, photographers, stylists, and makeup artists connect, collaborate, and create.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-ivory text-ink antialiased">

    {{-- NAVIGATION --}}
    <nav class="fixed top-0 w-full z-50 bg-ivory/90 backdrop-blur-sm border-b border-smoke">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">
                <a href="/" class="text-2xl font-display tracking-wider">
                    FASHIONCONNECT
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors duration-300 link-underline">About</a>
                    <a href="#features" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors duration-300 link-underline">Features</a>
                    <a href="#roles" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors duration-300 link-underline">Professionals</a>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors link-underline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-xs font-medium uppercase tracking-widest-xl bg-ink text-ivory px-5 py-2 hover:bg-charcoal transition-colors duration-300">Join</a>
                            @endif
                        @endauth
                    @endif
                </div>
                <button class="md:hidden" x-data @click="$dispatch('toggle-mobile-nav')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile nav --}}
    <div x-data="{ open: false }" @toggle-mobile-nav.window="open = !open" x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-ivory flex flex-col items-center justify-center gap-8 md:hidden">
        <button @click="open = false" class="absolute top-4 right-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <a href="#about" @click="open = false" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink">About</a>
        <a href="#features" @click="open = false" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink">Features</a>
        <a href="#roles" @click="open = false" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink">Professionals</a>
        <div class="divider w-12 my-2"></div>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-xs font-medium uppercase tracking-widest-xl text-ash">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-medium uppercase tracking-widest-xl text-ash">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-xs font-medium uppercase tracking-widest-xl bg-ink text-ivory px-6 py-2.5">Join</a>
                @endif
            @endauth
        @endif
    </div>

    <section class="min-h-screen flex items-end pb-16 pt-14 px-6 relative overflow-hidden">
      
        <div class="absolute inset-0">
            <img src="images/7.jpg"
                 alt="Fashion editorial"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-ivory/60"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto w-full">
            <p class="text-xs font-medium uppercase tracking-widest-xl text-ash mb-6 animate-reveal">The Professional Network for Fashion</p>
            <h1 class="font-display text-7xl sm:text-8xl md:text-[10rem] lg:text-[12rem] leading-[0.85] tracking-wider mb-8 animate-reveal" style="animation-delay: 0.1s">
                FASHION<br>CONNECT
            </h1>
            <div class="flex flex-col sm:flex-row items-start gap-8 animate-reveal" style="animation-delay: 0.3s">
                <p class="text-sm text-ash max-w-sm leading-relaxed">
                    Where designers, models, photographers, stylists & makeup artists build careers together.
                </p>
                <div class="flex items-center gap-6">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-ink text-ivory text-xs font-medium uppercase tracking-widest-xl px-8 py-3.5 hover:bg-charcoal transition-colors duration-300">
                            Get Started
                        </a>
                    @endif
                    <a href="#about" class="text-xs font-medium uppercase tracking-widest-xl text-ink link-underline">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT --}}
    <section id="about" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="divider mb-16"></div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div>
                    <p class="text-xs font-medium uppercase tracking-widest-xl text-ash mb-6">The Platform</p>
                    <h2 class="font-display text-5xl md:text-7xl tracking-wider leading-[0.9] mb-8">
                        YOUR PORTFOLIO.<br>YOUR NETWORK.<br>YOUR CAREER.
                    </h2>
                    <p class="text-sm text-ash leading-relaxed max-w-md mb-6">
                        FashionConnect replaces the chaos of scattered social media with a structured, professional ecosystem designed exclusively for the fashion industry.
                    </p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-xs font-medium uppercase tracking-widest-xl text-ink link-underline">Create your profile →</a>
                    @endif
                </div>
                <div>
                    <img src="images/4.jpg"
                         alt="Fashion designer at work"
                         class="w-full aspect-[4/5] object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="py-24 px-6 bg-cream">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16">
                <h2 class="font-display text-5xl md:text-7xl tracking-wider leading-[0.9]">CORE<br>FEATURES</h2>
                <p class="text-sm text-ash max-w-sm leading-relaxed mt-6 md:mt-0">Everything you need to showcase your work, find opportunities, and grow your career in fashion.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-16">
                @php
                $features = [
                    ['num' => '01', 'title' => 'Social Feed', 'desc' => 'Share images, like, comment, and follow. Your feed is your daily inspiration.'],
                    ['num' => '02', 'title' => 'Portfolio Profiles', 'desc' => 'Your posts become your portfolio. One profile, one gallery  effortlessly professional.'],
                    ['num' => '03', 'title' => 'Job Marketplace', 'desc' => 'Designers post jobs. Creatives apply. Photoshoots, campaigns, runway all here.'],
                    ['num' => '04', 'title' => 'Direct Messaging', 'desc' => 'Private conversations with verified professionals. Real connections, real work.'],
                    ['num' => '05', 'title' => 'Verified Badges', 'desc' => 'A blue tick that means something. Build trust across every interaction.'],
                    ['num' => '06', 'title' => 'Smart Discovery', 'desc' => 'Search by role, skills, or location. Find the exact collaborator you need.'],
                ];
                @endphp
                @foreach($features as $f)
                <div class="group">
                    <p class="text-xs text-silver mb-3">{{ $f['num'] }}</p>
                    <div class="w-8 h-px bg-ink mb-4 group-hover:w-16 transition-all duration-500"></div>
                    <h3 class="font-display text-2xl tracking-wider mb-3">{{ strtoupper($f['title']) }}</h3>
                    <p class="text-sm text-ash leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- IMAGE BREAK --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-px bg-smoke">
        <div class="bg-ivory">
            <img src="images/1.jpg"
                 alt="Fashion runway" class="w-full aspect-[3/4] object-cover hover:opacity-90 transition-opacity duration-500">
        </div>
        <div class="bg-ivory">
            <img src="images/2.jpg"
                 alt="Model portrait" class="w-full aspect-[3/4] object-cover hover:opacity-90 transition-opacity duration-500">
        </div>
        <div class="bg-ivory">
            <img src="images/3.jpg"
                 alt="Fashion photography" class="w-full aspect-[3/4] object-cover hover:opacity-90 transition-opacity duration-500">
        </div>
    </section>

    {{-- ROLES --}}
    <section id="roles" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <p class="text-xs font-medium uppercase tracking-widest-xl text-ash mb-6">Built For Every Creative</p>
                <h2 class="font-display text-5xl md:text-7xl tracking-wider leading-[0.9]">FIVE ROLES.<br>ONE PLATFORM.</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-px bg-smoke">
                @php
                $roles = [
                    ['title' => 'Designers', 'desc' => 'Post jobs & lead creative teams'],
                    ['title' => 'Photographers', 'desc' => 'Showcase & get booked for shoots'],
                    ['title' => 'Stylists', 'desc' => 'Display work & land styling gigs'],
                    ['title' => 'Makeup Artists', 'desc' => 'Build a client base with ease'],
                    ['title' => 'Models', 'desc' => 'Get discovered & apply to castings'],
                ];
                @endphp
                @foreach($roles as $r)
                <div class="bg-ivory p-8 text-center group hover:bg-cream transition-colors duration-500">
                    <h3 class="font-display text-xl tracking-wider mb-3">{{ strtoupper($r['title']) }}</h3>
                    <p class="text-xs text-ash leading-relaxed">{{ $r['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="py-24 px-6 bg-ink text-ivory">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs font-medium uppercase tracking-widest-xl text-silver mb-6">How It Works</p>
                    <h2 class="font-display text-5xl md:text-7xl tracking-wider leading-[0.9] mb-12">THREE<br>SIMPLE<br>STEPS</h2>
                    <div class="space-y-10">
                        @php
                        $steps = [
                            ['num' => '01', 'title' => 'Create', 'desc' => 'Sign up, pick your role, and upload your best work to build your portfolio.'],
                            ['num' => '02', 'title' => 'Connect', 'desc' => 'Follow creatives, discover collaborators, and search by skill or location.'],
                            ['num' => '03', 'title' => 'Collaborate', 'desc' => 'Apply to jobs, message professionals, get verified, and grow your career.'],
                        ];
                        @endphp
                        @foreach($steps as $s)
                        <div class="flex gap-6">
                            <span class="text-xs text-silver pt-1">{{ $s['num'] }}</span>
                            <div>
                                <h3 class="font-display text-2xl tracking-wider mb-2">{{ strtoupper($s['title']) }}</h3>
                                <p class="text-sm text-silver leading-relaxed">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <img src="images/5.jpg"
                         alt="Fashion collaboration"
                         class="w-full aspect-[4/5] object-cover opacity-80">
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-32 px-6 border-t border-smoke relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="font-display text-6xl md:text-8xl lg:text-9xl tracking-wider leading-[0.85] mb-8">
                READY TO<br>START?
            </h2>
            <p class="text-sm text-ash mb-10 max-w-md mx-auto">Join thousands of fashion professionals already building their careers on FashionConnect.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-block bg-ink text-ivory text-xs font-medium uppercase tracking-widest-xl px-10 py-4 hover:bg-charcoal transition-colors duration-300">
                    Create Your Account
                </a>
            @endif
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-smoke">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div>
                    <p class="font-display text-2xl tracking-wider mb-1">FASHIONCONNECT</p>
                    <p class="text-xs text-ash">The professional network for fashion.</p>
                </div>
                <div class="flex items-center gap-8">
                    <a href="#about" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors link-underline">About</a>
                    <a href="#features" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors link-underline">Features</a>
                    <a href="#roles" class="text-xs font-medium uppercase tracking-widest-xl text-ash hover:text-ink transition-colors link-underline">Professionals</a>
                </div>
            </div>
            <div class="divider mt-8 mb-6"></div>
            <p class="text-xs text-silver">&copy; {{ date('Y') }} FashionConnect. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

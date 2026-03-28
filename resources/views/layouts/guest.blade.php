<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FashionConnect') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 via-ivory to-gray-100">
            <div class="mb-2">
                <a href="/" class="font-display tracking-[0.2em] text-3xl font-semibold uppercase text-ink flex items-center gap-1">
                    FASHION<span class="text-gray-400">CONNECT</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white border border-gray-200 overflow-hidden shadow-xl sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400 tracking-widest uppercase font-semibold">&copy; {{ date('Y') }} FashionConnect</p>
        </div>
    </body>
</html>

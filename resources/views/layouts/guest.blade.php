<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'PDrive') }}</title>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased">
        <div class="flex min-h-full">
            {{-- Left panel (desktop) --}}
            <div class="hidden w-[420px] shrink-0 flex-col justify-between bg-gray-950 p-10 lg:flex">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-500 shadow-lg shadow-indigo-500/40">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-white">
                            <path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96ZM14 13v4h-4v-4H7l5-5 5 5h-3Z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-white tracking-tight">PDrive</span>
                </a>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400">Your personal cloud</p>
                    <h1 class="mt-4 text-4xl font-bold leading-tight tracking-tight text-white">Everything you need, right where you left it.</h1>
                    <p class="mt-5 text-base leading-7 text-gray-400">Upload, organize, preview, and share files from one clean private drive. No subscriptions, no ads, fully yours.</p>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">P</div>
                    <div>
                        <p class="text-xs font-semibold text-white">Self-hosted &amp; private</p>
                        <p class="text-xs text-gray-500">Your data stays on your server</p>
                    </div>
                </div>
            </div>

            {{-- Right panel (form) --}}
            <div class="flex flex-1 flex-col items-center justify-center bg-gray-50 px-4 py-12 sm:px-8">
                {{-- Mobile brand --}}
                <div class="mb-8 flex items-center gap-2.5 lg:hidden">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 shadow-lg shadow-indigo-600/30">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-white">
                            <path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96ZM14 13v4h-4v-4H7l5-5 5 5h-3Z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-900">PDrive</span>
                </div>

                <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

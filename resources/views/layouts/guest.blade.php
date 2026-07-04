<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PDrive') }}</title>

        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,#bae6fd,transparent_30%),linear-gradient(135deg,#e2e8f0,#f8fafc_35%,#dbeafe)] px-4 py-10">
            <div class="mx-auto grid min-h-[calc(100vh-5rem)] max-w-6xl items-center gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="hidden rounded-[2rem] bg-[linear-gradient(135deg,#082f49,#0f172a)] p-10 text-white shadow-[0_30px_80px_rgba(15,23,42,0.28)] lg:block">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                            <x-application-logo class="h-7 w-7 fill-current text-white" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-[0.35em] text-sky-200">PDRIVE</p>
                            <p class="text-sm text-sky-100/75">Self-hosted personal cloud storage</p>
                        </div>
                    </a>

                    <div class="mt-14 max-w-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-300">Secure workspace</p>
                        <h1 class="mt-4 text-5xl font-semibold tracking-tight">Keep every file, folder, and project under your control.</h1>
                        <p class="mt-6 text-lg leading-8 text-sky-100/80">
                            Sign in to upload, preview, favorite, search, recover from trash, and manage your own private drive from one clean dashboard.
                        </p>
                    </div>
                </div>

                <div class="mx-auto w-full max-w-md">
                    <div class="mb-6 text-center lg:hidden">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/20">
                                <x-application-logo class="h-7 w-7 fill-current" />
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold tracking-[0.35em] text-sky-700">PDRIVE</p>
                                <p class="text-sm text-slate-500">Personal cloud storage</p>
                            </div>
                        </a>
                    </div>

                    <div class="overflow-hidden rounded-[2rem] border border-white/60 bg-white/90 px-7 py-8 shadow-[0_30px_80px_rgba(15,23,42,0.12)] backdrop-blur">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

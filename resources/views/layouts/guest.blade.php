<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $siteName = $siteSettings['name'] ?? config('app.name', 'Laravel');
            $siteTagline = $siteSettings['tagline'] ?? 'Inventory & Experience Hub';
            $siteInitials = strtoupper(mb_substr($siteName, 0, 2));
        @endphp

        <title>{{ $siteName }} • Auth</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="font-sans text-slate-100 antialiased">
        <div class="relative min-h-screen overflow-hidden bg-slate-950 px-6 py-12">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-10 top-10 h-64 w-64 rounded-full bg-cyan-500/30 blur-3xl"></div>
                <div class="absolute bottom-10 right-0 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(15,23,42,0)_40%,_rgba(15,23,42,.95)_100%)]"></div>
            </div>

            <div class="relative mx-auto flex max-w-6xl flex-col gap-12 lg:grid lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div class="space-y-6 text-slate-100">
                    <a href="/" class="inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold backdrop-blur">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-lg font-semibold text-white">{{ $siteInitials }}</span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/60">{{ $siteName }}</p>
                            <p class="text-lg font-semibold">{{ $siteTagline }}</p>
                        </div>
                    </a>
                    <div class="space-y-4">
                        <h1 class="text-4xl font-semibold leading-tight text-white">Akses akun Anda dengan tampilan yang lebih personal dan aman.</h1>
                        <p class="text-sm text-white/70">Kelola pesanan, simpan invoice, dan lanjutkan pembayaran kapan pun dibutuhkan. Semua halaman autentikasi kini hadir dengan nuansa yang seragam.</p>
                    </div>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span> Sinkron dengan riwayat belanja Anda.</li>
                        <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span> Keamanan berlapis dengan konfirmasi email.</li>
                        <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span> Tampilan responsif untuk desktop dan mobile.</li>
                    </ul>
                </div>

                <div class="glass-panel w-full rounded-3xl border border-white/10 bg-white/95 px-8 py-10 text-slate-900 shadow-2xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

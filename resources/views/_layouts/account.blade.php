<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZARLY PETSHOP') }} • Account</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

@php
    $user = auth()->user();
    $navLinks = [
        ['label' => 'Ikhtisar', 'route' => 'account.dashboard'],
        ['label' => 'Riwayat Pesanan', 'route' => 'account.orders'],
        ['label' => 'Profil & Keamanan', 'route' => 'profile.edit'],
    ];
    $memberSince = optional($user?->created_at)->format('d M Y');
@endphp

<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-10 top-10 h-56 w-56 rounded-full bg-cyan-500/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-[-80px] h-72 w-72 rounded-full bg-indigo-500/30 blur-[120px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(15,23,42,0.4)_0%,_rgba(15,23,42,0.95)_60%)]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=140 height=140 viewBox=0 0 40 40 xmlns=http://www.w3.org/2000/svg%3E%3Cpath d=M0 39.5H39.5V0' fill='none' stroke='rgba(255,255,255,0.02)'/%3E%3C/svg%3E')] opacity-70"></div>
        </div>

        <div class="relative flex min-h-screen flex-col lg:h-screen lg:flex-row lg:overflow-hidden">
            <aside class="w-full border-b border-white/5 bg-white/5 px-6 py-8 backdrop-blur-xl lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-80 lg:flex-none lg:flex-col lg:overflow-y-auto lg:border-b-0 lg:border-r lg:px-8 lg:py-10">
                <div class="flex h-full flex-col gap-8">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-lg font-semibold">ZP</span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Zarly Petshop</p>
                            <p class="text-base font-semibold text-white">Account Center</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/10 px-5 py-6 text-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/40">Akun Anda</p>
                        <p class="mt-3 text-2xl font-semibold text-white">{{ $user?->name }}</p>
                        <p class="text-white/80">{{ $user?->email }}</p>
                        <p class="text-xs text-white/50">{{ $user?->phone ?? 'Nomor belum diisi' }}</p>
                        <div class="mt-4 flex flex-wrap gap-2 text-[11px] font-semibold text-white/70">
                            <span class="rounded-full border border-white/20 px-3 py-1">Member sejak {{ $memberSince ?? '—' }}</span>
                            <span class="rounded-full border border-white/20 px-3 py-1">Pesanan {{ number_format($user?->orders()->count() ?? 0) }}</span>
                        </div>
                    </div>

                    <nav class="space-y-2">
                        @foreach ($navLinks as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs($link['route']) ? 'bg-white text-slate-900 shadow-lg shadow-slate-900/15' : 'text-white/80 hover:bg-white/10' }}">
                                {{ $link['label'] }}
                                <span aria-hidden="true">→</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-auto flex flex-col gap-3 text-sm font-semibold">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 px-4 py-3 text-white/80 transition hover:border-white/30 hover:text-white">Kembali ke toko</a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200/40 px-4 py-3 text-rose-100 transition hover:border-rose-200 hover:text-white">Keluar</button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="flex-1 bg-gradient-to-b from-slate-50 to-white lg:h-screen lg:overflow-y-auto">
                <div class="relative min-h-full px-6 py-12 lg:px-16">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-white/90 to-transparent"></div>
                    <div class="relative z-10 space-y-8">
                        @yield('body')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>

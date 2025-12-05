<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = $siteSettings['name'] ?? config('app.name', 'Zarly Petshop');
        $siteTagline = $siteSettings['tagline'] ?? 'Account Center';
        $siteInitials = strtoupper(mb_substr($siteName, 0, 2));
    @endphp

    <title>{{ $siteName }} • Account</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>

    <script>
        (function () {
            const storageKey = 'zarly-theme-preference';
            const root = document.documentElement;
            const mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

            function resolve(preference) {
                if (preference === 'system') {
                    return mediaQuery && mediaQuery.matches ? 'dark' : 'light';
                }
                return preference;
            }

            function apply(preference) {
                const resolved = resolve(preference);
                root.classList.toggle('dark', resolved === 'dark');
                root.dataset.theme = resolved;
                root.dataset.themePreference = preference;
            }

            try {
                const saved = localStorage.getItem(storageKey) || 'system';
                apply(saved);
            } catch (error) {
                apply('system');
            }

            window.themeManager = function () {
                return {
                    storageKey: 'zarly-theme-preference',
                    current: 'system',
                    menuOpen: false,
                    mediaQuery: null,
                    init() {
                        this.mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
                        this.current = this.getStoredPreference();
                        this.applyTheme(this.current);
                        this.bindMediaListener();
                    },
                    getStoredPreference() {
                        try {
                            return localStorage.getItem(this.storageKey) || 'system';
                        } catch (error) {
                            return 'system';
                        }
                    },
                    persistPreference(value) {
                        try {
                            localStorage.setItem(this.storageKey, value);
                        } catch (error) {
                            /* ignore */
                        }
                    },
                    resolveTheme(value = this.current) {
                        if (value === 'system') {
                            return this.mediaQuery && this.mediaQuery.matches ? 'dark' : 'light';
                        }
                        return value;
                    },
                    applyTheme(value) {
                        const resolved = this.resolveTheme(value);
                        const rootEl = document.documentElement;
                        rootEl.classList.toggle('dark', resolved === 'dark');
                        rootEl.dataset.theme = resolved;
                        rootEl.dataset.themePreference = value;
                    },
                    setTheme(value) {
                        if (!value) return;
                        this.current = value;
                        this.persistPreference(value);
                        this.applyTheme(value);
                    },
                    isActive(value) {
                        return this.current === value;
                    },
                    bindMediaListener() {
                        if (!this.mediaQuery) return;
                        const handler = () => {
                            if (this.current === 'system') {
                                this.applyTheme('system');
                            }
                        };
                        if (this.mediaQuery.addEventListener) {
                            this.mediaQuery.addEventListener('change', handler);
                        } else if (this.mediaQuery.addListener) {
                            this.mediaQuery.addListener(handler);
                        }
                    },
                };
            };
        })();
    </script>
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

<body class="min-h-screen bg-gradient-to-b from-[#f8fbff] via-[#eef3fc] to-[#e3ebf7] text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-12 top-6 h-60 w-60 rounded-full bg-cyan-200/40 blur-[120px] dark:hidden"></div>
            <div class="absolute bottom-[-40px] right-[-60px] h-72 w-72 rounded-full bg-blue-200/40 blur-[140px] dark:hidden"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(248,250,252,0.9)_0%,_rgba(237,242,255,0.6)_45%,_rgba(255,255,255,0)_70%)] dark:hidden"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=160 height=160 viewBox=0 0 40 40 xmlns=http://www.w3.org/2000/svg%3E%3Cpath d=M0 39.5H39.5V0' fill='none' stroke='rgba(148,163,184,0.25)'/%3E%3C/svg%3E')] opacity-60 dark:hidden"></div>
            <div class="hidden h-full w-full bg-slate-950/90 dark:block"></div>
        </div>

        <div class="relative flex min-h-screen flex-col lg:h-screen lg:flex-row lg:overflow-hidden">
            <aside class="w-full border-b border-slate-200/80 bg-white/80 px-6 py-8 text-slate-700 shadow-xl backdrop-blur-2xl transition dark:border-[#1b2844] dark:bg-gradient-to-b dark:from-[#01030d]/95 dark:via-[#07122a]/92 dark:to-[#010409]/95 dark:text-[#dfe5ff] dark:shadow-[0_45px_120px_rgba(1,5,16,0.85)] lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-80 lg:flex-none lg:flex-col lg:overflow-y-auto lg:border-b-0 lg:border-r lg:px-8 lg:py-10">
                <div class="flex h-full flex-col gap-8">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-lg font-semibold text-slate-900 shadow-lg shadow-cyan-500/20 dark:bg-[#15233f] dark:text-white dark:shadow-[0_18px_45px_rgba(3,9,24,0.6)]">{{ $siteInitials }}</span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-[#9cb2e0]">{{ $siteName }}</p>
                            <p class="text-base font-semibold text-slate-900 dark:text-[#f5f7ff]">{{ $siteTagline }}</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white/90 px-5 py-6 text-sm shadow-lg shadow-slate-200/70 transition dark:border-[#22345a] dark:bg-[#0c1528] dark:text-[#e8edff] dark:shadow-[0_30px_85px_rgba(1,6,18,0.65)]">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-[#8ba1d4]">Akun Anda</p>
                        <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-[#f4f6ff]">{{ $user?->name }}</p>
                        <p class="text-slate-600 dark:text-[#c4d1f2]">{{ $user?->email }}</p>
                        <p class="text-xs text-slate-400 dark:text-[#8ca0cf]">{{ $user?->phone ?? 'Nomor belum diisi' }}</p>
                        <div class="mt-4 flex flex-wrap gap-2 text-[11px] font-semibold text-slate-600 dark:text-[#a9b9e7]">
                            <span class="rounded-full border border-slate-200 px-3 py-1 dark:border-[#2b416d] dark:text-[#d1dbff]">Member sejak {{ $memberSince ?? '—' }}</span>
                            <span class="rounded-full border border-slate-200 px-3 py-1 dark:border-[#2b416d] dark:text-[#d1dbff]">Pesanan {{ number_format($user?->orders()->count() ?? 0) }}</span>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white/85 px-5 py-4 text-xs font-semibold text-slate-500 shadow-lg shadow-slate-200/50 transition dark:border-[#1e2d4a] dark:bg-[#111b31]/90 dark:text-[#cad7ff] dark:shadow-[0_25px_70px_rgba(2,5,15,0.55)]" x-data="themeManager()" x-init="init()" x-cloak>
                        <p class="text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-[#7e91c3]">Tema tampilan</p>
                        <div class="mt-3 grid grid-cols-3 gap-2 text-[12px] font-semibold">
                            <button type="button" class="rounded-2xl border border-slate-200 px-3 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-[#25375a] dark:text-[#a7bce9] dark:hover:border-[#5de5ff] dark:hover:text-white" :class="{ 'bg-slate-900 text-white shadow-lg shadow-slate-900/15 dark:bg-[#e9f2ff] dark:text-[#0f1728]': isActive('light') }" @click="setTheme('light')">Light</button>
                            <button type="button" class="rounded-2xl border border-slate-200 px-3 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-[#25375a] dark:text-[#a7bce9] dark:hover:border-[#5de5ff] dark:hover:text-white" :class="{ 'bg-slate-900 text-white shadow-lg shadow-slate-900/15 dark:bg-[#e9f2ff] dark:text-[#0f1728]': isActive('dark') }" @click="setTheme('dark')">Dark</button>
                            <button type="button" class="rounded-2xl border border-slate-200 px-3 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-[#25375a] dark:text-[#a7bce9] dark:hover:border-[#5de5ff] dark:hover:text-white" :class="{ 'bg-slate-900 text-white shadow-lg shadow-slate-900/15 dark:bg-[#e9f2ff] dark:text-[#0f1728]': isActive('system') }" @click="setTheme('system')">System</button>
                        </div>
                    </div>

                    <nav class="space-y-2 text-sm font-semibold">
                        @foreach ($navLinks as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center justify-between rounded-2xl px-4 py-3 transition {{ request()->routeIs($link['route']) ? 'bg-white text-slate-900 shadow-lg shadow-slate-200 dark:bg-[#101a35] dark:text-[#eef1ff] dark:border dark:border-[#2b3f66] dark:shadow-[0_20px_60px_rgba(2,6,18,0.6)]' : 'text-slate-600 hover:bg-white/70 dark:text-[#96a8d4] dark:hover:bg-white/5 dark:hover:text-white' }}">
                                {{ $link['label'] }}
                                <span aria-hidden="true">→</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-auto flex flex-col gap-3 text-sm font-semibold">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-slate-400 hover:text-slate-900 dark:border-[#283a62] dark:text-[#cfd6f8] dark:hover:border-[#65e0ff] dark:hover:text-white">Kembali ke toko</a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200/80 px-4 py-3 text-rose-600 transition hover:border-rose-400 hover:text-rose-800 dark:border-[#ff8fb1]/50 dark:text-[#ffc1d2] dark:hover:border-[#ffd1dd]">Keluar</button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="flex-1 bg-gradient-to-b from-white via-[#eef3fc] to-[#e3ebf7] transition-colors duration-300 dark:bg-slate-950 dark:bg-none lg:h-screen lg:overflow-y-auto">
                <div class="relative min-h-full px-6 py-12 lg:px-16">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-white/90 via-slate-50/40 to-transparent dark:hidden"></div>
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

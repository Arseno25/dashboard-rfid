<header class="relative z-20 w-full">
    <div class="mx-auto mt-6 w-full max-w-6xl px-6 lg:px-8">
        <div class="glass-panel flex flex-wrap items-center justify-between gap-4 px-6 py-5">
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500 text-lg font-semibold text-white shadow-lg shadow-cyan-500/40">ZP</span>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Zarly Petshop</p>
                    <p class="text-lg font-semibold text-slate-900">Inventory & Experience Hub</p>
                </div>
            </div>
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }} transition-colors">Beranda</a>
                <a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }} transition-colors">Kategori</a>
                <a href="#contact" class="text-slate-500 transition-colors hover:text-slate-900">Kontak</a>
                <a href="#insight" class="text-slate-500 transition-colors hover:text-slate-900">Insight</a>
            </nav>
            <div class="hidden sm:flex items-center gap-3 text-sm font-semibold">
                @auth
                    @php
                        $user = auth()->user();
                        $initial = strtoupper(mb_substr($user?->name ?? 'U', 0, 1));
                    @endphp
                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open" class="inline-flex items-center gap-3 rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-cyan-600 text-sm font-semibold text-white">{{ $initial }}</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $user?->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div x-cloak x-show="open" x-transition @click.away="open = false" class="absolute right-0 z-30 mt-3 w-56 rounded-2xl border border-slate-100 bg-white p-3 text-sm font-semibold shadow-xl shadow-slate-900/10">
                            <a href="{{ route('account.dashboard') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50">
                                Dasbor
                                <span aria-hidden="true">↗</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50">Riwayat</a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-100 pt-3">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-rose-50 px-3 py-2 text-rose-600 transition hover:bg-rose-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Daftar</a>
                @endauth
                <button @click="cartOpen = true" class="relative inline-flex items-center gap-2 rounded-full bg-cyan-600 px-5 py-2 text-white shadow-lg shadow-cyan-600/40 transition hover:bg-cyan-500" data-cart-target>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 4h1.878a1 1 0 01.98.804L6.5 12.5m0 0-.63 3.146A2 2 0 007.842 18h10.316a2 2 0 001.972-1.684L21.5 9.5H7m-1.5 7.5a1 1 0 102 0 1 1 0 00-2 0zm10 0a1 1 0 102 0 1 1 0 00-2 0z" />
                    </svg>
                    Buka Keranjang
                    <span x-show="cartItemCount() > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-cyan-600 shadow-lg shadow-cyan-600/40" x-text="cartItemCount()"></span>
                </button>
            </div>
            <button @click="mobileNavOpen = !mobileNavOpen" class="ml-auto inline-flex items-center rounded-full border border-slate-200 p-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 lg:hidden" aria-label="Toggle navigation">
                <svg x-show="!mobileNavOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileNavOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="lg:hidden" x-cloak x-show="mobileNavOpen" x-transition>
            <div class="glass-panel mt-3 flex flex-col gap-4 px-6 py-5 text-sm font-semibold">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-slate-900' : 'text-slate-500' }}">Beranda</a>
                <a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'text-slate-900' : 'text-slate-500' }}">Kategori</a>
                <a href="#contact" class="text-slate-500">Kontak</a>
                <a href="#insight" class="text-slate-500">Insight</a>
                @auth
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-slate-700">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Masuk sebagai</p>
                        <p class="text-base font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('account.dashboard') }}" class="text-slate-500">Dasbor</a>
                    <a href="{{ route('account.orders') }}" class="text-slate-500">Riwayat</a>
                    <a href="{{ route('profile.edit') }}" class="text-slate-500">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 pt-3">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-rose-100 px-4 py-2 text-rose-500">Keluar</button>
                    </form>
                @else
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-slate-600">Masuk</a>
                        <a href="{{ route('register') }}" class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-slate-600">Daftar</a>
                    </div>
                @endauth
                <button @click="cartOpen = true; mobileNavOpen = false" class="relative mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-cyan-600 px-4 py-2 text-white shadow-lg shadow-cyan-600/40" data-cart-target>
                    Buka Keranjang
                    <span x-show="cartItemCount() > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-cyan-600 shadow-lg shadow-cyan-600/40" x-text="cartItemCount()"></span>
                </button>
            </div>
        </div>
    </div>
</header>

@php
    use Illuminate\Support\Facades\Storage;
    $siteName = $siteSettings['name'] ?? 'Zarly Petshop';
    $siteTagline = $siteSettings['tagline'] ?? 'Inventory & Experience Hub';
    $brandLogo = $siteSettings['brand_logo_path'] ?? null;
@endphp

<header class="relative z-20 w-full">
    <div class="mx-auto mt-6 w-full max-w-6xl px-6 lg:px-8">
        <div class="glass-panel flex flex-wrap items-center justify-between gap-4 px-6 py-5 lg:flex-nowrap">
            <div class="flex items-center gap-4">
                @if ($brandLogo)
                    <img src="{{ Storage::url($brandLogo) }}" alt="{{ $siteName }}" class="h-12 w-12 rounded-2xl border border-white/40 object-cover shadow-lg shadow-slate-900/20 dark:border-white/10" />
                @else
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500 text-lg font-semibold text-white shadow-lg shadow-cyan-500/40 dark:bg-cyan-400/80">{{ strtoupper(mb_substr($siteName, 0, 2)) }}</span>
                @endif
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-slate-300">{{ $siteName }}</p>
                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $siteTagline }}</p>
                </div>
            </div>
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-500 dark:text-slate-300">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white' }} transition-colors">Beranda</a>
                <a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white' }} transition-colors">Kategori</a>
                <a href="#contact" class="text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Kontak</a>
            </nav>
            <div class="hidden sm:flex items-center gap-3 text-sm font-semibold">
                <div class="relative" x-data="themeManager()" x-init="init()" @keydown.escape.stop="menuOpen = false">
                    <button type="button" @click="toggleMenu" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/20 dark:text-slate-200 dark:hover:text-white" aria-haspopup="true" :aria-expanded="menuOpen">
                        <span class="sr-only" x-text="'Tema ' + buttonLabel()"></span>
                        <template x-if="isActive('light')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414" />
                            </svg>
                        </template>
                        <template x-if="isActive('dark')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </template>
                        <template x-if="isActive('system')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-8 7h10M6 5h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                        </template>
                    </button>
                    <div x-cloak x-show="menuOpen" x-transition @click.away="menuOpen = false" class="absolute right-0 z-40 mt-2 w-56 rounded-2xl border border-slate-100 bg-white/95 p-3 text-sm shadow-xl shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900/90">
                        <button type="button" class="theme-option" :class="{ 'theme-option-active': isActive('light') }" @click="setTheme('light')">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414" />
                                </svg>
                                <span>Light</span>
                            </div>
                            <span class="text-xs text-slate-400 dark:text-slate-300" x-show="isActive('light')">Aktif</span>
                        </button>
                        <button type="button" class="mt-2 theme-option" :class="{ 'theme-option-active': isActive('dark') }" @click="setTheme('dark')">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                                </svg>
                                <span>Dark</span>
                            </div>
                            <span class="text-xs text-slate-400 dark:text-slate-300" x-show="isActive('dark')">Aktif</span>
                        </button>
                        <button type="button" class="mt-2 theme-option" :class="{ 'theme-option-active': isActive('system') }" @click="setTheme('system')">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-8 7h10M6 5h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                                <span>System</span>
                            </div>
                            <span class="text-xs text-slate-400 dark:text-slate-300" x-show="isActive('system')">Aktif</span>
                        </button>
                    </div>
                </div>
                @auth
                    @php
                        $user = auth()->user();
                        $initial = strtoupper(mb_substr($user?->name ?? 'U', 0, 1));
                    @endphp
                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open" class="inline-flex items-center gap-3 rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-cyan-600 text-sm font-semibold text-white">{{ $initial }}</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-white">{{ $user?->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div x-cloak x-show="open" x-transition @click.away="open = false" class="absolute right-0 z-30 mt-3 w-56 rounded-2xl border border-slate-100 bg-white p-3 text-sm font-semibold shadow-xl shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900/95">
                            <a href="{{ route('account.dashboard') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/40">
                                Dasbor
                                <span aria-hidden="true">↗</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/40">Riwayat</a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/40">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-100 pt-3 dark:border-white/10">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-rose-50 px-3 py-2 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-100 dark:hover:bg-rose-500/20">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white">Daftar</a>
                @endauth
                <button @click="cartOpen = true" class="relative inline-flex h-11 w-11 items-center justify-center rounded-full bg-cyan-600 text-white shadow-lg shadow-cyan-600/40 transition hover:bg-cyan-500" data-cart-target>
                    <span class="sr-only">Buka keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 4h1.878a1 1 0 01.98.804L6.5 12.5m0 0-.63 3.146A2 2 0 007.842 18h10.316a2 2 0 001.972-1.684L21.5 9.5H7m-1.5 7.5a1 1 0 102 0 1 1 0 00-2 0zm10 0a1 1 0 102 0 1 1 0 00-2 0z" />
                    </svg>
                    <span x-show="cartItemCount() > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-cyan-600 shadow-lg shadow-cyan-600/40" x-text="cartItemCount()"></span>
                </button>
            </div>
            <button @click="mobileNavOpen = !mobileNavOpen" class="ml-auto inline-flex items-center rounded-full border border-slate-200 p-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white lg:hidden" aria-label="Toggle navigation">
                <svg x-show="!mobileNavOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileNavOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="lg:hidden" x-cloak x-show="mobileNavOpen" x-transition>
            <div class="glass-panel mt-3 flex flex-col gap-4 px-6 py-5 text-sm font-semibold text-slate-600 dark:text-slate-200">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-300' }}">Beranda</a>
                <a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-300' }}">Kategori</a>
                <a href="#contact" class="text-slate-500 dark:text-slate-300">Kontak</a>
                <div class="mt-2 rounded-2xl border border-slate-100 bg-white/80 px-4 py-4 text-xs font-semibold uppercase tracking-[0.3em] text-slate-400 dark:border-white/10 dark:bg-slate-900/70 dark:text-slate-300">
                    <p>Mode tampilan</p>
                    <div class="mt-3 space-y-2 text-[11px] normal-case" x-data="themeManager()" x-init="init()">
                        <button type="button" class="theme-option" :class="{ 'theme-option-active': isActive('light') }" @click="setTheme('light')">Light</button>
                        <button type="button" class="theme-option" :class="{ 'theme-option-active': isActive('dark') }" @click="setTheme('dark')">Dark</button>
                        <button type="button" class="theme-option" :class="{ 'theme-option-active': isActive('system') }" @click="setTheme('system')">System</button>
                    </div>
                </div>
                @auth
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-slate-700 dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-200">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-slate-300">Masuk sebagai</p>
                        <p class="text-base font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('account.dashboard') }}" class="text-slate-500 dark:text-slate-300">Dasbor</a>
                    <a href="{{ route('account.orders') }}" class="text-slate-500 dark:text-slate-300">Riwayat</a>
                    <a href="{{ route('profile.edit') }}" class="text-slate-500 dark:text-slate-300">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 pt-3 dark:border-white/10">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-rose-100 px-4 py-2 text-rose-500 dark:border-rose-500/40 dark:text-rose-100">Keluar</button>
                    </form>
                @else
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-slate-600 dark:border-white/15 dark:text-slate-200">Masuk</a>
                        <a href="{{ route('register') }}" class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-slate-600 dark:border-white/15 dark:text-slate-200">Daftar</a>
                    </div>
                @endauth
                <button @click="cartOpen = true; mobileNavOpen = false" class="relative mt-2 inline-flex h-11 w-11 items-center justify-center rounded-full bg-cyan-600 text-white shadow-lg shadow-cyan-600/40" data-cart-target>
                    <span class="sr-only">Buka keranjang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 4h1.878a1 1 0 01.98.804L6.5 12.5m0 0-.63 3.146A2 2 0 007.842 18h10.316a2 2 0 001.972-1.684L21.5 9.5H7m-1.5 7.5a1 1 0 102 0 1 1 0 00-2 0zm10 0a1 1 0 102 0 1 1 0 00-2 0z" />
                    </svg>
                    <span x-show="cartItemCount() > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-cyan-600 shadow-lg shadow-cyan-600/40" x-text="cartItemCount()"></span>
                </button>
            </div>
        </div>
    </div>
</header>

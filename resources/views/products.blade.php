@extends('_layouts.master')

@section('body')
@php
    $activeDiscount = optional($discount)->percentage ?? 0;
    $discountLabel = optional($discount)->name ?? 'Promo belum tersedia';
    $productCount = $product->total();
    $siteName = $siteSettings['name'] ?? 'Zarly Petshop';
    $heroTitle = $siteSettings['hero_title'] ?? 'Kurasi kebutuhan hewan kesayangan dalam satu dasbor elegan.';
    $heroSubtitle = $siteSettings['hero_subtitle'] ?? 'Pantau stok, promo, dan performa produk dengan tampilan yang rapi agar setiap pelanggan menemukan perlengkapan terbaiknya.';
@endphp

<div x-data="productExplorer({ endpoint: '{{ route('search') }}' })" x-init="boot(@js($search ?? ''))" class="space-y-10">
<section class="glass-panel px-8 py-10">
    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ $siteName }}</p>
            <h1 class="mt-3 text-4xl font-semibold text-slate-900 dark:text-white">{{ $heroTitle }}</h1>
            <p class="mt-4 text-base text-slate-500 dark:text-slate-300">{{ $heroSubtitle }}</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="stat-tile">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Produk aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($productCount) }}</p>
                    <p class="text-xs text-slate-400">Terakhir diperbarui {{ now()->format('d M Y') }}</p>
                </div>
                <div class="stat-tile">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Diskon berjalan</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $activeDiscount }}%</p>
                    <p class="text-xs text-slate-400">{{ $discountLabel }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-3xl border border-white/40 bg-gradient-to-br from-cyan-500 via-sky-500 to-indigo-500 p-1 shadow-2xl shadow-cyan-500/40 backdrop-blur-md dark:border-white/10 dark:from-[#0f172a] dark:via-[#0b1120] dark:to-[#020617] dark:shadow-black/40">
            <div class="h-full w-full rounded-[28px] border border-white/30 bg-white/95 p-6 shadow-xl shadow-slate-900/5 dark:border-white/10 dark:bg-slate-950/80 dark:text-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Cari produk</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan kata kunci merek atau kategori untuk menemukan produk favorit.</p>
                <form method="GET" action="{{ route('search') }}" class="mt-6 space-y-4" x-on:submit.prevent="manualSubmit">
                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 shadow-inner shadow-slate-100 dark:border-white/10 dark:bg-slate-900/50 dark:shadow-black/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                        <input type="search" name="query" id="query" placeholder="Nama produk, kategori, dsb" value="{{ $search ?? '' }}" x-model.trim="query" x-on:input="handleInput" x-on:keydown.enter.prevent class="w-full border-none bg-transparent text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 dark:text-white dark:placeholder:text-slate-500" />
                    </label>
                    <div class="flex items-center justify-end text-xs text-slate-400 dark:text-slate-500">
                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-cyan-600 dark:bg-white/10 dark:text-white dark:hover:bg-cyan-600/80">Cari</button>
                    </div>
                </form>
                <div class="mt-4 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
                    <p x-show="query" x-cloak>Filter: <span class="font-semibold text-slate-900 dark:text-white" x-text="query"></span></p>
                    <span x-show="isLoading" x-cloak class="animate-pulse text-cyan-600">Memuat hasil...</span>
                </div>
            </div>
        </div>
    </div>
</section>
<div x-ref="resultsContainer" x-bind:aria-busy="isLoading">
    @include('partials.products-grid', [
        'product' => $product,
        'activeDiscount' => $activeDiscount,
        'productCount' => $productCount,
        'search' => $search ?? null,
    ])
</div>
</div>

@push('scripts')
<script>
    window.productExplorer = function ({ endpoint }) {
        return {
            endpoint,
            query: '',
            isLoading: false,
            debounceTimer: null,
            debounceDelay: 350,
            instantFetchGap: 600,
            activeController: null,
            cache: new Map(),
            maxCacheEntries: 8,
            lastFetchAt: 0,
            initialLoading: true,
            initialDelayTimer: null,
            minInitialDelay: 3000,
            pendingNetwork: false,
            boot(initialQuery = '') {
                this.query = initialQuery || '';
                const initialKey = this.cacheKey(initialQuery || '');
                this.cache.set(initialKey, this.$refs.resultsContainer.innerHTML);
                this.startInitialDelay();
            },
            handleInput() {
                this.scheduleFetch();
            },
            startInitialDelay() {
                this.isLoading = true;
                this.initialLoading = true;
                if (this.initialDelayTimer) {
                    clearTimeout(this.initialDelayTimer);
                }
                this.initialDelayTimer = setTimeout(() => {
                    this.initialLoading = false;
                    if (!this.pendingNetwork) {
                        this.isLoading = false;
                    }
                }, this.minInitialDelay);
            },
            scheduleFetch() {
                if (this.debounceTimer) {
                    clearTimeout(this.debounceTimer);
                }
                const now = Date.now();
                if (now - this.lastFetchAt >= this.instantFetchGap) {
                    this.fetchResults();
                    return;
                }
                this.debounceTimer = setTimeout(() => this.fetchResults(), this.debounceDelay);
            },
            async manualSubmit() {
                if (this.debounceTimer) {
                    clearTimeout(this.debounceTimer);
                }
                await this.fetchResults({ force: true });
            },
            cacheKey(raw = null) {
                const value = (raw ?? this.query).trim().toLowerCase();
                return value.length ? value : '__all__';
            },
            storeCache(key, html) {
                this.cache.set(key, html);
                while (this.cache.size > this.maxCacheEntries) {
                    const oldestKey = this.cache.keys().next().value;
                    this.cache.delete(oldestKey);
                }
            },
            async fetchResults({ force = false } = {}) {
                const params = new URLSearchParams();
                if (this.query.trim().length) {
                    params.append('query', this.query.trim());
                }
                const url = params.toString() ? `${this.endpoint}?${params.toString()}` : this.endpoint;
                this.pendingNetwork = true;
                this.isLoading = true;
                const cacheKey = this.cacheKey();
                if (!force && this.cache.has(cacheKey)) {
                    this.$refs.resultsContainer.innerHTML = this.cache.get(cacheKey);
                    this.lastFetchAt = Date.now();
                    this.pendingNetwork = false;
                    if (!this.initialLoading) {
                        this.isLoading = false;
                    }
                    return;
                }
                if (this.activeController) {
                    this.activeController.abort();
                }
                const controller = new AbortController();
                this.activeController = controller;
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        signal: controller.signal,
                    });
                    const payload = await response.json();
                    if (payload?.html) {
                        this.$refs.resultsContainer.innerHTML = payload.html;
                        this.storeCache(cacheKey, payload.html);
                    }
                } catch (error) {
                    if (error.name === 'AbortError') {
                        this.pendingNetwork = false;
                        return;
                    }
                    this.$refs.resultsContainer.innerHTML = '<div class="glass-panel p-6 text-center text-sm text-rose-500">Gagal memuat data produk.</div>';
                } finally {
                    if (this.activeController === controller) {
                        this.activeController = null;
                    }
                    this.pendingNetwork = false;
                    if (!this.initialLoading) {
                        this.isLoading = false;
                    }
                    this.lastFetchAt = Date.now();
                }
            },
        };
    }
</script>
@endpush

@endsection

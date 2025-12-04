@extends('_layouts.master')

@section('body')
@php
    $activeDiscount = optional($discount)->percentage ?? 0;
    $discountLabel = optional($discount)->name ?? 'Promo belum tersedia';
    $productCount = $product->total();
@endphp

<div x-data="productExplorer({ endpoint: '{{ route('search') }}' })" x-init="boot(@js($search ?? ''))" class="space-y-10">
<section class="glass-panel px-8 py-10">
    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Etalase unggulan</p>
            <h1 class="mt-3 text-4xl font-semibold text-slate-900">Kurasi kebutuhan hewan kesayangan dalam satu dasbor elegan.</h1>
            <p class="mt-4 text-base text-slate-500">Pantau stok, promo, dan performa produk dengan tampilan yang rapi agar setiap pelanggan menemukan perlengkapan terbaiknya.</p>
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
        <div class="rounded-3xl bg-gradient-to-br from-cyan-500 via-sky-500 to-indigo-500 p-1 shadow-2xl">
            <div class="h-full w-full rounded-[28px] bg-white/95 p-6">
                <h2 class="text-lg font-semibold text-slate-900">Cari produk</h2>
                <p class="text-sm text-slate-500">Masukkan kata kunci merek atau kategori untuk menemukan produk favorit.</p>
                <form method="GET" action="{{ route('search') }}" class="mt-6 space-y-4" x-on:submit.prevent="manualSubmit">
                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-inner shadow-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                        <input type="search" name="query" id="query" placeholder="Nama produk, kategori, dsb" value="{{ $search ?? '' }}" x-model.trim="query" x-on:input="handleInput" x-on:keydown.enter.prevent class="w-full border-none bg-transparent text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0" />
                    </label>
                    <div class="flex items-center justify-end text-xs text-slate-400">
                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">Cari</button>
                    </div>
                </form>
                <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                    <p x-show="query" x-cloak>Filter: <span class="font-semibold text-slate-900" x-text="query"></span></p>
                    <span x-show="isLoading" x-cloak class="animate-pulse text-cyan-600">Memuat hasil...</span>
                </div>
            </div>
        </div>
    </div>
</section>
<div x-ref="resultsContainer">
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
            boot(initialQuery = '') {
                this.query = initialQuery || '';
            },
            handleInput() {
                this.scheduleFetch();
            },
            scheduleFetch() {
                if (this.debounceTimer) {
                    clearTimeout(this.debounceTimer);
                }
                this.debounceTimer = setTimeout(() => this.fetchResults(), 3000);
            },
            async manualSubmit() {
                if (this.debounceTimer) {
                    clearTimeout(this.debounceTimer);
                }
                await this.fetchResults();
            },
            async fetchResults() {
                const params = new URLSearchParams();
                if (this.query.trim().length) {
                    params.append('query', this.query.trim());
                }
                const url = params.toString() ? `${this.endpoint}?${params.toString()}` : this.endpoint;
                this.isLoading = true;
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });
                    const payload = await response.json();
                    if (payload?.html) {
                        this.$refs.resultsContainer.innerHTML = payload.html;
                    }
                } catch (error) {
                    this.$refs.resultsContainer.innerHTML = '<div class="glass-panel p-6 text-center text-sm text-rose-500">Gagal memuat data produk.</div>';
                } finally {
                    this.isLoading = false;
                }
            },
        };
    }
</script>
@endpush

@endsection

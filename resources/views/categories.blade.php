@extends('_layouts.master')

@section('body')
@php
    $categoryCount = $categories->count();
@endphp

<section class="glass-panel px-8 py-10">
    <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Koleksi kategori</p>
    <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-4xl font-semibold text-slate-900">Lengkapi kebutuhan hewan kesayangan melalui kategori pilihan.</h1>
            <p class="mt-4 text-base text-slate-500">Pakan premium, vitamin, aksesori, dan perlengkapan grooming tersusun rapi agar pelanggan mudah menemukan favoritnya.</p>
        </div>
        <div class="stat-tile text-right">
            <p class="text-xs uppercase tracking-wide text-slate-400">Ringkasan</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($totalProducts) }}</p>
            <p class="text-xs text-slate-400">Produk aktif di {{ $categoryCount }} kategori</p>
        </div>
    </div>
</section>

<section class="mt-12 space-y-8">
    @forelse ($categories as $category)
        @php
            $featuredProducts = $category->products->take(3);
        @endphp
        <article class="glass-panel px-6 py-7">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Kategori</p>
                    <h2 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $category->products->count() }} produk siap kirim</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Lihat semua produk</a>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredProducts as $product)
                    <div class="glass-panel flex flex-col overflow-hidden border border-slate-100">
                        <div class="relative h-40 w-full overflow-hidden">
                            @if ($product->getFirstMediaUrl('product_image'))
                                <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                            @else
                                <img src="{{ asset('default.png') }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                            @endif
                            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-cyan-600">Stok {{ $product->stock }}</span>
                        </div>
                        <div class="flex flex-1 flex-col px-4 py-4">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                            <p class="mt-auto text-base font-semibold text-slate-900">{{ formatCurrency($product->price) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="glass-panel col-span-full flex flex-col items-center justify-center gap-2 px-6 py-12 text-center">
                        <p class="text-lg font-semibold text-slate-900">Belum ada produk di kategori ini.</p>
                        <p class="text-sm text-slate-500">Tambah produk untuk menampilkan rekomendasi.</p>
                    </div>
                @endforelse
            </div>
        </article>
    @empty
        <div class="glass-panel px-6 py-16 text-center">
            <p class="text-2xl font-semibold text-slate-900">Kategori belum tersedia</p>
            <p class="mt-2 text-sm text-slate-500">Tambahkan kategori terlebih dulu untuk menata katalog.</p>
        </div>
    @endforelse
</section>
@endsection

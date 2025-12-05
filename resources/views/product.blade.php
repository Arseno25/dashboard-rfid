@extends('_layouts.master')

@section('body')
@php
    $productEntity = $product ?? null;
    $categoryName = $productEntity ? optional($productEntity->category)->name : 'Kategori belum ditentukan';
    $stock = $productEntity->stock ?? 0;
    $basePrice = $productEntity->price ?? 0;
    $description = $productEntity->description ?? 'Deskripsi produk akan tampil di sini.';
    $relationDiscount = $productEntity ? optional($productEntity->discounts)->percentage : null;
    $detailDiscount = $relationDiscount ?? optional($discount ?? null)->percentage ?? 0;
    $finalPrice = max($basePrice - (($basePrice * $detailDiscount) / 100), 0);
    $mediaUrl = $productEntity && $productEntity->getFirstMediaUrl('product_image') ? $productEntity->getFirstMediaUrl('product_image') : asset('default.png');
@endphp

<div class="grid gap-10 lg:grid-cols-[1.6fr,1fr]" data-product-card>
    <div class="glass-panel overflow-hidden">
        <div class="relative aspect-square w-full overflow-hidden rounded-[28px] bg-slate-100">
            <img src="{{ $mediaUrl }}" alt="{{ $productEntity->name ?? 'Produk' }}" class="h-full w-full object-cover" data-product-image />
            <span class="absolute left-6 top-6 rounded-full bg-white/90 px-4 py-2 text-xs font-semibold text-cyan-600">{{ $categoryName }}</span>
            <span class="absolute right-6 top-6 rounded-full bg-slate-900/90 px-4 py-2 text-xs font-semibold text-white">Stok {{ $stock }}</span>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="stat-tile">
                <p class="text-xs uppercase tracking-wide text-slate-400">Harga normal</p>
                <p class="text-xl font-semibold text-slate-900">{{ formatCurrency($basePrice) }}</p>
            </div>
            <div class="stat-tile">
                <p class="text-xs uppercase tracking-wide text-slate-400">Diskon</p>
                <p class="text-xl font-semibold text-slate-900">{{ $detailDiscount }}%</p>
            </div>
            <div class="stat-tile">
                <p class="text-xs uppercase tracking-wide text-slate-400">Harga akhir</p>
                <p class="text-xl font-semibold text-slate-900">{{ formatCurrency($finalPrice) }}</p>
            </div>
        </div>
    </div>

    <div class="glass-panel px-8 py-10">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Detail produk</p>
        <h1 class="mt-3 text-4xl font-semibold text-slate-900">{{ $productEntity->name ?? 'Nama produk' }}</h1>
        <p class="mt-4 text-sm text-slate-500">{{ $description }}</p>
        <dl class="mt-6 space-y-4 text-sm text-slate-600">
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <dt>Kategori</dt>
                <dd class="font-semibold text-slate-900">{{ $categoryName }}</dd>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <dt>Stok tersedia</dt>
                <dd class="font-semibold text-slate-900">{{ $stock }}</dd>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <dt>Status</dt>
                <dd class="font-semibold text-cyan-600">{{ $productEntity && $productEntity->is_enabled ? 'Aktif' : 'Tidak aktif' }}</dd>
            </div>
        </dl>
        <div class="mt-8 flex flex-col gap-3 text-sm text-slate-500">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                Harga sudah termasuk pajak dan siap dikirim ke seluruh Indonesia.
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                Diskon otomatis diterapkan sesuai promo yang sedang berlangsung.
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                Foto menampilkan varian asli sehingga pelanggan tahu apa yang diterima.
            </div>
        </div>
        @php
            $detailCartPayload = [
                'id' => $productEntity->id ?? time(),
                'name' => $productEntity->name ?? 'Produk pilihan',
                'price' => $finalPrice,
                'image' => $mediaUrl,
                'quantity' => 1,
            ];
        @endphp
        <div class="mt-10 flex flex-col gap-3 sm:flex-row">
            <button @click='addToCart(@json($detailCartPayload), $event)' class="flex-1 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600">Tambah ke keranjang</button>
            <a href="{{ url()->previous() }}" class="flex-1 rounded-full border border-slate-200 px-6 py-3 text-center text-sm font-semibold text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Kembali</a>
        </div>
    </div>
</div>

<section class="mt-12 grid gap-6 md:grid-cols-3">
    <article class="glass-panel p-6">
        <h3 class="text-lg font-semibold text-slate-900">Komposisi</h3>
        <p class="mt-2 text-sm text-slate-500">Tuliskan bahan utama, rasa, atau kandungan nutrisi yang membantu hewan tetap sehat.</p>
    </article>
    <article class="glass-panel p-6">
        <h3 class="text-lg font-semibold text-slate-900">Petunjuk penyimpanan</h3>
        <p class="mt-2 text-sm text-slate-500">Infokan cara penyimpanan terbaik agar kualitas produk tetap terjaga.</p>
    </article>
    <article class="glass-panel p-6">
        <h3 class="text-lg font-semibold text-slate-900">Catatan RFID</h3>
        <p class="mt-2 text-sm text-slate-500">Cantumkan nomor batch atau UID tag untuk pelacakan stok berbasis RFID.</p>
    </article>
</section>
@endsection

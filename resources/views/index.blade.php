@extends('_layouts.master')

@section('body')
@php
    $siteName = $siteSettings['name'] ?? 'Zarly Petshop';
    $heroTitle = $siteSettings['hero_title'] ?? 'Dashboard RFID untuk petshop yang gesit dan premium.';
    $heroSubtitle = $siteSettings['hero_subtitle'] ?? 'Visual profesional agar tim toko dapat mempromosikan produk unggulan dengan cepat.';
@endphp

<section class="glass-panel px-8 py-12">
    <div class="grid gap-10 lg:grid-cols-2">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ $siteName }}</p>
            <h1 class="mt-3 text-4xl font-semibold text-slate-900">{{ $heroTitle }}</h1>
            <p class="mt-4 text-base text-slate-500">{{ $heroSubtitle }}</p>
            <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-500">
                <span class="rounded-full border border-slate-200 px-4 py-2">Realtime stock</span>
                <span class="rounded-full border border-slate-200 px-4 py-2">Insight pelanggan</span>
                <span class="rounded-full border border-slate-200 px-4 py-2">Monitoring diskon</span>
            </div>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full bg-cyan-600 px-6 py-3 text-white shadow-lg shadow-cyan-600/40 transition hover:bg-cyan-500">Masuk ke katalog</a>
                <button @click="cartOpen = true" class="relative inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">
                    Lihat keranjang demo
                    <span x-show="cartItemCount() > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-cyan-600 text-[10px] font-semibold text-white shadow-lg shadow-cyan-600/30" x-text="cartItemCount()"></span>
                </button>
            </div>
        </div>
        <div class="rounded-[28px] border border-white/40 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 text-white shadow-2xl">
            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Snapshot</p>
            <h2 class="mt-3 text-3xl font-semibold">Key metrics</h2>
            <div class="mt-8 grid gap-6 sm:grid-cols-2">
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-xs text-white/60">Produk aktif</p>
                    <p class="mt-3 text-3xl font-semibold">120</p>
                    <p class="text-xs text-white/50">Stok sehat siap dikirim</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-xs text-white/60">Diskon rata-rata</p>
                    <p class="mt-3 text-3xl font-semibold">18%</p>
                    <p class="text-xs text-white/50">Sesuai model Discount aktif</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-xs text-white/60">Reorder alert</p>
                    <p class="mt-3 text-3xl font-semibold">9 SKU</p>
                    <p class="text-xs text-white/50">Stok &lt; 5 otomatis ditandai</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-5">
                    <p class="text-xs text-white/60">Waktu update</p>
                    <p class="mt-3 text-3xl font-semibold">{{ now()->format('H:i') }} WIB</p>
                    <p class="text-xs text-white/50">Mengacu data terbaru</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="insight" class="mt-12 grid gap-8 md:grid-cols-3">
    <article class="glass-panel p-6">
        <h3 class="text-xl font-semibold text-slate-900">Sinyal permintaan</h3>
        <p class="mt-2 text-sm text-slate-500">Pantau kategori paling sering dicari lalu optimalkan visibilitas produk.</p>
        <ul class="mt-4 space-y-2 text-sm text-slate-600">
            <li>1. Pakan kucing premium</li>
            <li>2. Vitamin anjing aktif</li>
            <li>3. Aksesori grooming</li>
        </ul>
    </article>
    <article class="glass-panel p-6">
        <h3 class="text-xl font-semibold text-slate-900">Checkout modern</h3>
        <p class="mt-2 text-sm text-slate-500">Halaman checkout baru menampilkan langkah kontak, pengiriman, hingga ringkasan order dengan tipografi profesional.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-flex text-sm font-semibold text-cyan-600 hover:text-cyan-500">Lihat contoh alur →</a>
    </article>
    <article class="glass-panel p-6">
        <h3 class="text-xl font-semibold text-slate-900">Integrasi siap</h3>
        <p class="mt-2 text-sm text-slate-500">Tampilan bersih ini mudah dipadukan dengan sistem kasir, marketplace, atau kanal pemasaran favorit Anda.</p>
        <p class="mt-4 text-sm text-slate-600">Tetap ringan, tetap responsif, siap mendukung penjualan.</p>
    </article>
</section>
@endsection

@extends('_layouts.master')

@section('body')
<section class="glass-panel px-8 py-12">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Selamat datang</p>
                <h1 class="mt-3 text-4xl font-semibold text-slate-900">Zarly Petshop Dashboard</h1>
                <p class="mt-4 text-base text-slate-500">Tempat terbaik untuk memamerkan koleksi perlengkapan hewan, menerima pesanan, dan mengelola stok secara elegan.</p>
            </div>
            @if (Route::has('login'))
                <div class="flex gap-3 text-sm font-semibold">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-full border border-slate-200 px-5 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Masuk kios</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-200 px-5 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-full bg-slate-900 px-5 py-2 text-white transition hover:bg-cyan-600">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <div class="grid gap-8 md:grid-cols-2">
            <article class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-inner shadow-slate-100">
                <h2 class="text-xl font-semibold text-slate-900">Apa yang baru?</h2>
                <p class="mt-3 text-sm text-slate-500">Antarmuka segar dengan penekanan pada storytelling produk, CTA jelas, dan pengalaman checkout modern.</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                    <li>• Layout glassmorphism dengan tipografi modern.</li>
                    <li>• Komponen siap pakai untuk hero, kartu produk, dan timeline checkout.</li>
                    <li>• Akses cepat menuju katalog melalui tombol CTA.</li>
                </ul>
                <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-cyan-600 hover:text-cyan-500">Lihat katalog →</a>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-inner shadow-slate-100">
                <h2 class="text-xl font-semibold text-slate-900">Teknologi yang digunakan</h2>
                <p class="mt-3 text-sm text-slate-500">Didukung oleh stack ringan dan cepat sehingga mudah disesuaikan dengan kebutuhan toko.</p>
                <div class="mt-4 grid gap-3 text-xs uppercase tracking-[0.3em] text-slate-400">
                    <span>TailwindCSS + Alpine</span>
                    <span>Laravel Blade</span>
                    <span>RFID Commerce Suite</span>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="mt-12 grid gap-6 md:grid-cols-3">
    <article class="glass-panel p-6">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Langkah 1</p>
        <h3 class="mt-2 text-lg font-semibold text-slate-900">Masuk / daftar</h3>
        <p class="mt-2 text-sm text-slate-500">Gunakan tombol di atas untuk login atau registrasi sebelum mengakses dashboard.</p>
    </article>
    <article class="glass-panel p-6">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Langkah 2</p>
        <h3 class="mt-2 text-lg font-semibold text-slate-900">Kelola produk</h3>
        <p class="mt-2 text-sm text-slate-500">Atur katalog, diskon, dan stok untuk memastikan setiap pelanggan menemukan produk idealnya.</p>
    </article>
    <article class="glass-panel p-6">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Langkah 3</p>
        <h3 class="mt-2 text-lg font-semibold text-slate-900">Monitoring checkout</h3>
        <p class="mt-2 text-sm text-slate-500">Halaman checkout baru siap dipakai untuk presentasi maupun implementasi akhir.</p>
    </article>
</section>
@endsection

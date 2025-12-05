@extends('_layouts.account')

@php
    use Illuminate\Support\Str;

    $totalOrders = $stats['total_orders'] ?? 0;
    $completedOrders = $stats['completed_orders'] ?? 0;
    $pendingOrders = $stats['pending_payments'] ?? 0;
    $totalSpent = $stats['total_spent'] ?? 0;

    $invoiceReady = $recentOrders->filter(fn ($order) => filled($order->invoice_path))->take(4);
    $timelineOrders = $recentOrders->take(5);
@endphp

@section('body')
<div class="space-y-12">
    {{-- Hero Section --}}
    <section class="relative overflow-hidden rounded-[36px] border border-white/70 bg-white/95 px-8 py-10 text-slate-900 shadow-2xl shadow-slate-200/70 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/60">
        <div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(circle at top, rgba(59,130,246,0.15), transparent 60%);"></div>
        <div class="relative flex flex-wrap gap-10">
            <div class="flex-1 space-y-5">
                <p class="text-xs uppercase tracking-[0.6em] text-slate-500 dark:text-cyan-300">Customer Journey</p>
                <h1 class="text-4xl font-semibold leading-tight text-slate-900 dark:text-white">Halo {{ Str::of($user->name)->headline() }}, semua transaksi aman terkendali.</h1>
                <p class="max-w-2xl text-base text-slate-500 dark:text-white/70">Gunakan panel ini untuk memantau pembayaran, mengecek status pesanan, dan mengunduh invoice tanpa perlu membuka halaman lain.</p>
                <div class="flex flex-wrap gap-3 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-white transition hover:bg-slate-800 dark:bg-cyan-500 dark:text-slate-950 dark:hover:bg-cyan-400">
                        Lanjut belanja
                        <span aria-hidden="true">↗</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-3 text-slate-700 transition hover:border-slate-500 hover:text-slate-900 dark:border-white/30 dark:text-white/80 dark:hover:text-white">
                        Kelola profil
                    </a>
                    <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-3 text-slate-700 transition hover:border-slate-500 hover:text-slate-900 dark:border-white/30 dark:text-white/80 dark:hover:text-white">
                        Riwayat pesanan
                    </a>
                </div>
            </div>
            <div class="w-full max-w-sm rounded-3xl border border-white/70 bg-white/90 p-6 text-sm shadow-lg shadow-slate-200/60 dark:border-[#25345b] dark:bg-[#090f1f]/95 dark:text-[#eef2ff] dark:shadow-[0_25px_80px_rgba(4,6,15,0.75)]">
                <p class="text-xs uppercase tracking-[0.5em] text-slate-500 dark:text-white/60">Ringkasan akun</p>
                <dl class="mt-5 space-y-4 text-slate-900 dark:text-white">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Total pesanan</dt>
                        <dd class="text-2xl font-semibold">{{ number_format($totalOrders) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Menunggu pembayaran</dt>
                        <dd class="text-2xl font-semibold text-amber-500 dark:text-amber-300">{{ number_format($pendingOrders) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Belanja seumur akun</dt>
                        <dd class="text-2xl font-semibold">{{ formatCurrency($totalSpent, 'Rp. ', 0) }}</dd>
                    </div>
                </dl>
                <div class="mt-6 grid gap-3 text-xs text-slate-600 dark:text-white/70">
                    <div class="rounded-2xl border border-slate-200 px-4 py-3 dark:border-white/10">Email: {{ $user->email }}</div>
                    <div class="rounded-2xl border border-slate-200 px-4 py-3 dark:border-white/10">Telepon: {{ $user->phone ?? 'Belum diisi' }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Snapshot metrics --}}
    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-lg shadow-slate-200/60 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/60">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Belanja kumulatif</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ formatCurrency($totalSpent, 'Rp. ', 0) }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Total nilai dari semua pesanan selesai.</p>
        </article>
        <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-lg shadow-slate-200/60 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/60">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Pesanan selesai</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600 dark:text-emerald-300">{{ number_format($completedOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Invoice siap unduh kapan saja.</p>
        </article>
        <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-lg shadow-slate-200/60 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/60">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Pesanan pending</p>
            <p class="mt-3 text-3xl font-semibold text-amber-500 dark:text-amber-300">{{ number_format($pendingOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Segera selesaikan pembayaran sebelum stok habis.</p>
        </article>
        <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-lg shadow-slate-200/60 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/60">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Jumlah pesanan</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Termasuk status menunggu, diproses, dan selesai.</p>
        </article>
    </section>

    {{-- Journey & Actions --}}
    <section class="grid gap-6 2xl:grid-cols-[1.5fr_1fr]">
        <article class="rounded-[32px] border border-white/70 bg-white/95 p-6 text-slate-900 shadow-xl shadow-slate-200/70 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/50">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Rangkuman perjalanan</p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Aktivitas terbaru</h2>
                </div>
                <a href="{{ route('account.orders') }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/30 dark:text-white/80 dark:hover:text-white">Lihat semua pesanan</a>
            </div>
            <div class="mt-6 space-y-6">
                @forelse ($timelineOrders as $order)
                    <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white px-5 py-4 text-slate-800 shadow-sm transition sm:flex-row sm:items-center sm:gap-6 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-200">
                        <div class="flex w-full flex-col text-sm font-semibold text-slate-900 sm:w-40 dark:text-white">
                            <span>{{ optional($order->created_at)->format('d M Y') }}</span>
                            <span class="text-xs font-normal text-slate-500 dark:text-slate-400">{{ optional($order->created_at)->format('H:i') }}</span>
                        </div>
                        <div class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $order->quantity }} item • {{ formatCurrency($order->total ?? 0, 'Rp. ', 0) }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[11px] font-semibold">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 dark:bg-white/10 dark:text-white">{{ ucfirst($order->status) }}</span>
                            <span class="rounded-full bg-cyan-100 px-3 py-1 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-200">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white/70">Belum ada transaksi tercatat. Yuk mulai belanja!</div>
                @endforelse
            </div>
        </article>

        <div class="space-y-6">
            <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-xl shadow-slate-200/70 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/50">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/60">Tindakan cepat</p>
                <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Status pesanan aktif</h2>
                @if($upcomingOrder)
                    <div class="mt-5 rounded-3xl border border-amber-200 bg-amber-50 p-5 text-sm text-slate-800 shadow-inner dark:border-amber-400/30 dark:bg-slate-900/70 dark:text-amber-50">
                        <p class="text-xs uppercase tracking-[0.4em] text-amber-500 dark:text-amber-200">Menunggu tindakan</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $upcomingOrder->order_number }}</p>
                        <dl class="mt-4 space-y-2 text-slate-700 dark:text-white/80">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-white/70">Status pesanan</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ ucfirst($upcomingOrder->status) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-white/70">Pembayaran</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ ucfirst($upcomingOrder->payment_status) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500 dark:text-white/70">Total</dt>
                                <dd class="font-semibold text-slate-900 dark:text-white">{{ formatCurrency($upcomingOrder->total ?? 0, 'Rp. ', 0) }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('account.orders') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-cyan-500 dark:text-slate-950 dark:hover:bg-cyan-400">Selesaikan pembayaran</a>
                    </div>
                @else
                    <div class="mt-5 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-slate-800 shadow-inner dark:border-emerald-400/30 dark:bg-slate-900/70 dark:text-emerald-50">
                        <p class="text-xs uppercase tracking-[0.4em] text-emerald-500 dark:text-emerald-200">Semua aman</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Tidak ada pesanan tertunda</p>
                        <p class="mt-3 text-sm text-slate-600 dark:text-emerald-50/80">Gunakan momen ini untuk menjelajah produk baru atau memperbarui data profil Anda.</p>
                        <div class="mt-4 flex flex-col gap-3">
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/20 dark:text-white/80 dark:hover:text-white">Mulai belanja</a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-white/20 dark:text-white/80 dark:hover:text-white">Perbarui profil</a>
                        </div>
                    </div>
                @endif
            </article>

            <article class="rounded-3xl border border-white/70 bg-white/95 p-6 text-slate-900 shadow-xl shadow-slate-200/70 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/50">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/60">Invoice siap unduh</p>
                <div class="mt-4 space-y-3">
                    @forelse ($invoiceReady as $order)
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900/70 dark:text-white/80 dark:hover:text-white">
                            <span>{{ $order->order_number }}</span>
                            <span class="text-xs text-slate-400 dark:text-white/50">{{ optional($order->invoice_sent_at ?? $order->updated_at)->format('d M Y') }}</span>
                        </a>
                    @empty
                        <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-white/70">Invoice akan ditampilkan otomatis setelah pembayaran berhasil.</p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>

    {{-- Assistance Strip --}}
    <section class="rounded-[32px] border border-white/70 bg-white/95 p-6 text-slate-900 shadow-xl shadow-slate-200/70 transition dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/50">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Tips keamanan</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Pastikan nomor telepon aktif dan email terverifikasi supaya notifikasi pembayaran selalu sampai.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Kelola invoice</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Semua invoice tersimpan permanen. Anda dapat mengunduh ulang kapan pun di menu Riwayat pesanan.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/50">Butuh bantuan?</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Jika pembayaran pending lebih dari 24 jam, hubungi admin agar stok tetap diamankan untuk Anda.</p>
            </div>
        </div>
    </section>
</div>
@endsection

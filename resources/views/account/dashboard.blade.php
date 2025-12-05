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
    <section class="relative overflow-hidden rounded-[36px] border border-slate-100 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-8 py-10 text-white shadow-2xl shadow-slate-900/30">
        <div class="absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at top, rgba(14,165,233,0.4), transparent 60%);"></div>
        <div class="relative flex flex-wrap gap-10">
            <div class="flex-1 space-y-5">
                <p class="text-xs uppercase tracking-[0.6em] text-cyan-300">Customer Journey</p>
                <h1 class="text-4xl font-semibold leading-tight">Halo {{ Str::of($user->name)->headline() }}, semua transaksi aman terkendali.</h1>
                <p class="max-w-2xl text-base text-white/70">Gunakan panel ini untuk memantau pembayaran, mengecek status pesanan, dan mengunduh invoice tanpa perlu membuka halaman lain.</p>
                <div class="flex flex-wrap gap-3 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-3 text-white transition hover:bg-white/20">
                        Lanjut belanja
                        <span aria-hidden="true">↗</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-5 py-3 text-white/80 transition hover:border-white hover:text-white">
                        Kelola profil
                    </a>
                    <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-5 py-3 text-white/80 transition hover:border-white hover:text-white">
                        Riwayat pesanan
                    </a>
                </div>
            </div>
            <div class="w-full max-w-sm rounded-3xl border border-white/20 bg-white/10 p-6 text-sm backdrop-blur">
                <p class="text-xs uppercase tracking-[0.5em] text-white/70">Ringkasan akun</p>
                <dl class="mt-5 space-y-4 text-white">
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Total pesanan</dt>
                        <dd class="text-2xl font-semibold">{{ number_format($totalOrders) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Menunggu pembayaran</dt>
                        <dd class="text-2xl font-semibold text-amber-300">{{ number_format($pendingOrders) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Belanja seumur akun</dt>
                        <dd class="text-2xl font-semibold">{{ formatCurrency($totalSpent, 'Rp. ', 0) }}</dd>
                    </div>
                </dl>
                <div class="mt-6 grid gap-3 text-xs">
                    <div class="rounded-2xl border border-white/30 px-4 py-3 text-white/80">Email: {{ $user->email }}</div>
                    <div class="rounded-2xl border border-white/30 px-4 py-3 text-white/80">Telepon: {{ $user->phone ?? 'Belum diisi' }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Snapshot metrics --}}
    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Belanja kumulatif</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ formatCurrency($totalSpent, 'Rp. ', 0) }}</p>
            <p class="mt-1 text-sm text-slate-500">Total nilai dari semua pesanan selesai.</p>
        </article>
        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pesanan selesai</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ number_format($completedOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500">Invoice siap unduh kapan saja.</p>
        </article>
        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pesanan pending</p>
            <p class="mt-3 text-3xl font-semibold text-amber-600">{{ number_format($pendingOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500">Segera selesaikan pembayaran sebelum stok habis.</p>
        </article>
        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Jumlah pesanan</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totalOrders) }}</p>
            <p class="mt-1 text-sm text-slate-500">Termasuk status menunggu, diproses, dan selesai.</p>
        </article>
    </section>

    {{-- Journey & Actions --}}
    <section class="grid gap-6 2xl:grid-cols-[1.5fr_1fr]">
        <article class="rounded-[32px] border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Rangkuman perjalanan</p>
                    <h2 class="text-2xl font-semibold text-slate-900">Aktivitas terbaru</h2>
                </div>
                <a href="{{ route('account.orders') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Lihat semua pesanan</a>
            </div>
            <div class="mt-6 space-y-6">
                @forelse ($timelineOrders as $order)
                    <div class="flex flex-col gap-4 rounded-3xl border border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:gap-6">
                        <div class="flex w-full flex-col text-sm font-semibold text-slate-900 sm:w-40">
                            <span>{{ optional($order->created_at)->format('d M Y') }}</span>
                            <span class="text-xs font-normal text-slate-500">{{ optional($order->created_at)->format('H:i') }}</span>
                        </div>
                        <div class="flex-1 text-sm text-slate-600">
                            <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-slate-500">{{ $order->quantity }} item • {{ formatCurrency($order->total ?? 0, 'Rp. ', 0) }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[11px] font-semibold">
                            <span class="rounded-full bg-white px-3 py-1 text-slate-600">{{ ucfirst($order->status) }}</span>
                            <span class="rounded-full bg-slate-900/10 px-3 py-1 text-slate-900">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-sm text-slate-500">Belum ada transaksi tercatat. Yuk mulai belanja!</div>
                @endforelse
            </div>
        </article>

        <div class="space-y-6">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Tindakan cepat</p>
                <h2 class="mt-2 text-xl font-semibold text-slate-900">Status pesanan aktif</h2>
                @if($upcomingOrder)
                    <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50/70 p-5 text-sm text-slate-700">
                        <p class="text-xs uppercase tracking-[0.4em] text-amber-500">Menunggu tindakan</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $upcomingOrder->order_number }}</p>
                        <dl class="mt-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Status pesanan</dt>
                                <dd class="font-semibold text-slate-900">{{ ucfirst($upcomingOrder->status) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Pembayaran</dt>
                                <dd class="font-semibold text-slate-900">{{ ucfirst($upcomingOrder->payment_status) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Total</dt>
                                <dd class="font-semibold text-slate-900">{{ formatCurrency($upcomingOrder->total ?? 0, 'Rp. ', 0) }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('account.orders') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-600">Selesaikan pembayaran</a>
                    </div>
                @else
                    <div class="mt-5 rounded-3xl border border-emerald-100 bg-emerald-50/70 p-5 text-sm text-slate-700">
                        <p class="text-xs uppercase tracking-[0.4em] text-emerald-500">Semua aman</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">Tidak ada pesanan tertunda</p>
                        <p class="mt-3 text-sm text-slate-600">Gunakan momen ini untuk menjelajah produk baru atau memperbarui data profil Anda.</p>
                        <div class="mt-4 flex flex-col gap-3">
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900">Mulai belanja</a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900">Perbarui profil</a>
                        </div>
                    </div>
                @endif
            </article>

            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Invoice siap unduh</p>
                <div class="mt-4 space-y-3">
                    @forelse ($invoiceReady as $order)
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-200">
                            <span>{{ $order->order_number }}</span>
                            <span class="text-xs text-slate-400">{{ optional($order->invoice_sent_at ?? $order->updated_at)->format('d M Y') }}</span>
                        </a>
                    @empty
                        <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-5 text-center text-sm text-slate-500">Invoice akan ditampilkan otomatis setelah pembayaran berhasil.</p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>

    {{-- Assistance Strip --}}
    <section class="rounded-[32px] border border-slate-100 bg-white p-6 shadow-sm">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Tips keamanan</p>
                <p class="mt-2 text-sm text-slate-600">Pastikan nomor telepon aktif dan email terverifikasi supaya notifikasi pembayaran selalu sampai.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Kelola invoice</p>
                <p class="mt-2 text-sm text-slate-600">Semua invoice tersimpan permanen. Anda dapat mengunduh ulang kapan pun di menu Riwayat pesanan.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Butuh bantuan?</p>
                <p class="mt-2 text-sm text-slate-600">Jika pembayaran pending lebih dari 24 jam, hubungi admin agar stok tetap diamankan untuk Anda.</p>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('_layouts.account')

@section('body')
<section class="rounded-[32px] border border-white/70 bg-white/95 px-8 py-10 text-slate-900 shadow-2xl shadow-slate-200/70 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:shadow-slate-950/50">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/60">Riwayat transaksi</p>
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">Pesanan saya</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Pantau status pembayaran, unduh invoice, dan catat kebutuhan pelanggan Anda.</p>
        </div>
        <div class="flex flex-wrap gap-3 text-sm font-semibold">
            <a href="{{ route('account.dashboard') }}" class="rounded-full border border-slate-300 px-4 py-2 text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-slate-700 dark:text-slate-200 dark:hover:border-cyan-400">Kembali ke dasbor</a>
            <a href="{{ route('profile.edit') }}" class="rounded-full border border-slate-300 px-4 py-2 text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 dark:border-slate-700 dark:text-slate-200 dark:hover:border-cyan-400">Perbarui profil</a>
        </div>
    </div>

    <div class="mt-8 space-y-5">
        @forelse ($orders as $order)
            <article class="rounded-3xl border border-white/70 bg-white p-6 shadow-lg shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/80">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-slate-400">Order</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ optional($order->created_at)->format('d M Y H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 dark:bg-white/10 dark:text-white">{{ ucfirst($order->status) }}</span>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-200">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                </div>
                <div class="mt-4 grid gap-4 text-sm text-slate-600 dark:text-slate-300 sm:grid-cols-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Total</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ formatCurrency($order->total ?? 0, 'Rp. ', 0) }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Jumlah item</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $order->quantity }} produk</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Catatan</p>
                        <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $order->buyer_note ?? '—' }}</p>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm font-semibold">
                    @if($order->invoice_path)
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2 text-white transition hover:bg-slate-800 dark:bg-cyan-500 dark:text-slate-950 dark:hover:bg-cyan-400">
                            Lihat invoice
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-4-4v8m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </a>
                    @else
                        <span class="rounded-full border border-dashed border-slate-300 px-4 py-2 text-slate-400 dark:border-slate-700 dark:text-slate-500">Invoice disiapkan setelah pembayaran selesai.</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white/80 p-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-300">
                Belum ada transaksi. Mulai belanja dan kembali ke halaman ini untuk memantau riwayat Anda.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</section>
@endsection

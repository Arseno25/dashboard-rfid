@extends('_layouts.master')

@section('body')
<div class="grid gap-10 lg:grid-cols-[1.4fr,1fr]">
    <section class="glass-panel px-8 py-10">
        <div class="flex flex-col gap-4">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Checkout journey</p>
            <h1 class="text-3xl font-semibold text-slate-900">Selesaikan transaksi pelanggan dengan tiga langkah jelas.</h1>
        </div>

        <ol class="mt-8 space-y-6">
            <li class="rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-sm shadow-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-600 text-sm font-semibold text-white">1</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Data kontak</p>
                            <p class="text-xs text-slate-500">Otomatis mengikuti profil akun Anda.</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-cyan-600">aktif</span>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <label class="text-xs font-semibold text-slate-500">Nama lengkap
                        <input type="text" x-model="checkoutForm.name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="Nama akun" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Email
                        <input type="email" x-model="checkoutForm.email" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="Email login" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Nomor WhatsApp
                        <input type="tel" x-model="checkoutForm.phone" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="08xxxxxxxx" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Catatan internal
                        <input type="text" x-model="checkoutForm.note" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0" placeholder="Pelanggan VIP" />
                    </label>
                </div>
                <p class="mt-3 text-xs text-slate-400">Perbarui nama, email, atau nomor telepon melalui halaman profil akun.</p>
            </li>
            <li class="rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-sm shadow-slate-200">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">2</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Pengiriman</p>
                        <p class="text-xs text-slate-500">Metode kirim dan alamat lengkap.</p>
                    </div>
                </div>
                <div class="mt-5 space-y-4">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <label class="flex flex-1 items-center gap-3 rounded-2xl border border-cyan-300 bg-cyan-50 px-4 py-3 text-sm font-semibold text-cyan-700">
                            <input type="radio" name="shipping" checked class="h-4 w-4 border-cyan-300 text-cyan-600 focus:ring-cyan-500" />
                            Kurir internal • 2 jam
                        </label>
                        <label class="flex flex-1 items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-600">
                            <input type="radio" name="shipping" class="h-4 w-4 border-slate-300 text-cyan-600 focus:ring-cyan-500" />
                            Ekspedisi partner
                        </label>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <label class="text-xs font-semibold text-slate-500">Provinsi
                            <select class="mt-2 w-full rounded-2xl border border-slate-200 px-3 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0">
                                <option>DKI Jakarta</option>
                                <option>Jawa Barat</option>
                            </select>
                        </label>
                        <label class="text-xs font-semibold text-slate-500">Kota
                            <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 px-3 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0" placeholder="Jakarta Selatan" />
                        </label>
                        <label class="text-xs font-semibold text-slate-500">Tanggal kirim
                            <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 px-3 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0" />
                        </label>
                    </div>
                    <label class="text-xs font-semibold text-slate-500">Alamat lengkap
                        <textarea class="mt-2 w-full rounded-2xl border border-slate-200 px-3 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0" rows="3" placeholder="Nama jalan, nomor rumah, patokan"></textarea>
                    </label>
                </div>
            </li>
            <li class="rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-sm shadow-slate-200">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">3</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Pembayaran</p>
                        <p class="text-xs text-slate-500">Metode akan muncul setelah isi langkah sebelumnya.</p>
                    </div>
                </div>
            </li>
        </ol>

        <div class="mt-8 flex flex-col gap-3 text-sm font-semibold sm:flex-row">
            <button class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 px-6 py-3 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </button>
            <button type="button" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60" @click="proceedCheckout" :disabled="midtransProcessing || !cartItems.length || !isAuthenticated">
                Lanjut ke pembayaran
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>

    <aside class="glass-panel h-fit px-8 py-10">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Ringkasan</p>
                <h2 class="text-2xl font-semibold text-slate-900">Order total (2)</h2>
            </div>
            <button class="text-xs font-semibold text-cyan-600">Edit</button>
        </div>
        <div class="mt-6 space-y-4">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white/80 p-4">
                <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=200&q=80" alt="Product" class="h-16 w-16 rounded-2xl object-cover" />
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-900">RFID Collar</p>
                    <p class="text-xs text-slate-500">Qty 2 • SKU 0012</p>
                </div>
                <p class="text-sm font-semibold text-slate-900">{{ formatCurrency(250000) }}</p>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white/80 p-4">
                <img src="https://images.unsplash.com/photo-1504593811423-6dd665756598?auto=format&fit=crop&w=200&q=80" alt="Product" class="h-16 w-16 rounded-2xl object-cover" />
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-900">Premium Cat Food</p>
                    <p class="text-xs text-slate-500">Qty 1 • SKU 0431</p>
                </div>
                <p class="text-sm font-semibold text-slate-900">{{ formatCurrency(180000) }}</p>
            </div>
        </div>
        <div class="mt-6 space-y-3 text-sm text-slate-600">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="font-semibold text-slate-900">{{ formatCurrency(430000) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Diskon aktif</span>
                <span class="font-semibold text-cyan-600">-{{ formatCurrency(43000) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pengiriman</span>
                <span class="font-semibold text-slate-900">{{ formatCurrency(20000) }}</span>
            </div>
            <div class="flex justify-between border-t border-slate-100 pt-3 text-base font-semibold text-slate-900">
                <span>Total</span>
                <span>{{ formatCurrency(407000) }}</span>
            </div>
        </div>
        <p class="mt-4 text-xs text-slate-400">Angka di atas merupakan contoh visual; sesuaikan dengan nilai transaksi sebenarnya.</p>
    </aside>
</div>
@endsection

<div x-cloak x-show="paymentMethodModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center px-4" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/70" @click="closePaymentMethodModal()"></div>
    <div class="relative w-full max-w-4xl rounded-[32px] bg-white p-8 shadow-2xl" x-transition>
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Detail pelanggan</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-900">Selesaikan pembayaran</h3>
                <p class="mt-2 text-sm text-slate-500">Informasi pelanggan otomatis mengikuti akun Anda.</p>
                <p class="mt-1 text-[11px] text-slate-400">Perbarui data melalui <a href="{{ route('profile.edit') }}" class="font-semibold text-cyan-600">halaman profil</a>.</p>
            </div>
            <button type="button" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-slate-300 hover:text-slate-900 disabled:cursor-not-allowed disabled:opacity-50" @click="closePaymentMethodModal()" :disabled="midtransProcessing">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-6 flex flex-col gap-6 lg:flex-row">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Profil pelanggan</p>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-300">Informasi pesanan</span>
                </div>
                <div class="mt-4 space-y-4">
                    <label class="text-xs font-semibold text-slate-500">Nama lengkap
                        <input type="text" x-model="checkoutForm.name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="Nama pelanggan" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Email
                        <input type="email" x-model="checkoutForm.email" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="Email aktif" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Nomor telepon / WhatsApp
                        <input type="tel" x-model="checkoutForm.phone" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900" placeholder="08xxxxxxxx" readonly />
                    </label>
                    <label class="text-xs font-semibold text-slate-500">Catatan internal
                        <textarea x-model="checkoutForm.note" rows="2" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:border-cyan-400 focus:outline-none focus:ring-0" placeholder="Catatan opsional"></textarea>
                    </label>
                </div>
            </div>
            <div class="flex-1 rounded-[28px] border border-slate-100 bg-slate-50/70 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Ringkasan order</p>
                <h4 class="mt-1 text-xl font-semibold text-slate-900">Pembayaran</h4>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Total produk</span>
                        <span class="font-semibold text-slate-900" x-text="cartItemCount()"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="text-base font-semibold text-slate-900" x-text="formatCurrency(cartSubtotal())"></span>
                    </div>
                </div>
                <div class="mt-5 max-h-48 space-y-3 overflow-y-auto">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-3 py-2 text-xs">
                            <div>
                                <p class="font-semibold text-slate-900" x-text="item.name"></p>
                                <p class="text-slate-500" x-text="`Qty ${item.quantity}`"></p>
                            </div>
                            <span class="font-semibold text-slate-900" x-text="formatCurrency((item.price || 0) * (item.quantity || 1))"></span>
                        </div>
                    </template>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl border border-cyan-100 bg-white/80 px-4 py-3 text-sm text-slate-600">
                        Setelah menekan tombol di bawah, Anda akan diarahkan ke halaman pembayaran aman untuk menyelesaikan transaksi.
                    </div>
                    <button type="button" class="w-full rounded-2xl bg-slate-900 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60" :disabled="midtransProcessing" @click="startMidtransCheckout()">
                        <span x-show="!midtransProcessing">Lanjutkan pembayaran</span>
                        <span x-show="midtransProcessing">Menyiapkan halaman…</span>
                    </button>
                </div>
                <p class="mt-3 text-[11px] text-slate-400">Transaksi diproses secara aman dan dapat dipantau pada halaman ini.</p>
            </div>
        </div>
    </div>
</div>

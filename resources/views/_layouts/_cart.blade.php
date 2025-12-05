<div x-cloak x-show="cartOpen" class="fixed inset-0 z-[99] flex" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-950/80" @click="cartOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    <section class="ml-auto flex h-full w-full max-w-md flex-col bg-white/95 shadow-2xl shadow-slate-900/10 backdrop-blur-lg dark:bg-slate-950/95 dark:shadow-black/40 z-50" x-transition:enter="transform transition ease-out duration-400" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0">
        <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5 dark:border-white/10">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">Keranjang</p>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Ringkasan pesanan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-300">Tambahkan produk dari katalog untuk mulai checkout.</p>
            </div>
            <button @click="cartOpen = false" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-cyan-300 hover:text-slate-900 dark:border-white/10 dark:text-slate-300 dark:hover:text-white" aria-label="Close cart">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 space-y-5 overflow-y-auto px-6 py-6">
            <div class="rounded-3xl border border-dashed border-slate-200/80 bg-slate-50/90 px-4 py-4 text-sm text-slate-500 dark:border-white/15 dark:bg-white/5 dark:text-slate-300">
                Tambahkan produk favorit pelanggan, atur jumlahnya, dan lihat total belanja secara instan di sini.
            </div>
            <template x-if="cartItems.length === 0">
                <div class="rounded-3xl bg-white/70 px-4 py-6 text-center shadow-inner shadow-slate-200 dark:bg-slate-900/70 dark:shadow-black/20">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-200">Keranjang Anda masih kosong.</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-400">Eksplor katalog untuk memasukkan produk pilihan.</p>
                </div>
            </template>
            <template x-for="(item, index) in cartItems" :key="item.id">
                <div class="flex items-start gap-4 rounded-3xl border border-slate-100 bg-white px-4 py-4 shadow-sm dark:border-white/10 dark:bg-slate-900/80">
                    <img :src="item.image || '{{ asset('default.png') }}'" alt="Produk" class="h-16 w-16 rounded-2xl object-cover ring-1 ring-white/60 dark:ring-white/10" />
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="item.name"></p>
                        <div class="mt-3 flex items-center gap-3">
                            <span class="text-[11px] uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Qty</span>
                            <div class="flex items-center gap-2">
                                <button type="button" class="h-8 w-8 rounded-full border border-slate-200 text-sm font-semibold text-slate-600 transition hover:border-cyan-300 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white" @click="updateItemQuantity(index, -1)" aria-label="Kurangi jumlah">−</button>
                                <span class="w-10 text-center text-sm font-semibold text-slate-900 dark:text-white" x-text="item.quantity ?? 1"></span>
                                <button type="button" class="h-8 w-8 rounded-full border border-slate-200 text-sm font-semibold text-slate-600 transition hover:border-cyan-300 hover:text-slate-900 dark:border-white/15 dark:text-slate-200 dark:hover:text-white" @click="updateItemQuantity(index, 1)" aria-label="Tambah jumlah">+</button>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-white" x-text="formatCurrency((item.price || 0) * (item.quantity || 1))"></p>
                    </div>
                    <button class="text-xs font-semibold text-rose-500 hover:text-rose-600 dark:text-rose-300 dark:hover:text-rose-200" @click="removeItem(index)">Hapus</button>
                </div>
            </template>
        </div>

        <div class="border-t border-slate-100 px-6 py-6 dark:border-white/10">
            <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-300">
                <span>Subtotal</span>
                <span class="text-lg font-semibold text-slate-900 dark:text-white" x-text="formatCurrency(cartSubtotal())">{{ formatCurrency(0) }}</span>
            </div>
            <button type="button" class="mt-4 w-full rounded-2xl bg-slate-900 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white/10 dark:text-white dark:hover:bg-cyan-600/90" @click="proceedCheckout" :disabled="!cartItems.length || midtransProcessing || !isAuthenticated">
                Lanjut ke pembayaran
            </button>
            <p class="mt-3 text-center text-xs text-slate-400 dark:text-slate-400" x-show="isAuthenticated">Pastikan profil Anda berisi nama, email, dan nomor telepon sebelum checkout.</p>
            <p class="mt-3 text-center text-xs text-slate-400 dark:text-slate-400" x-show="!isAuthenticated">Masuk ke akun Anda untuk membuka akses checkout.</p>
        </div>
    </section>
</div>

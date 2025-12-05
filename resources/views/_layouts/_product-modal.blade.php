<div x-cloak x-show="detailModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/70" @click="closeProductDetail"></div>
    <div class="relative mx-4 w-full max-w-3xl rounded-[32px] bg-white p-6 shadow-2xl" x-transition data-product-card>
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex-1">
                <div class="overflow-hidden rounded-3xl bg-slate-100">
                    <img :src="detailProduct?.image || @json(asset('default.png'))" :alt="detailProduct?.name || 'Produk'" class="h-64 w-full object-cover" data-product-image />
                </div>
            </div>
            <div class="flex flex-1 flex-col gap-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Detail produk</p>
                        <h3 class="mt-2 text-3xl font-semibold text-slate-900" x-text="detailProduct?.name || 'Produk'">Produk</h3>
                        <p class="text-sm text-slate-500" x-text="detailProduct?.category || 'Tanpa kategori'"></p>
                    </div>
                    <button class="rounded-full border border-slate-200 p-2 text-slate-500 hover:text-slate-900" @click="closeProductDetail" aria-label="Tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-slate-600" x-text="detailProduct?.description || 'Belum ada deskripsi produk.'"></p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs text-slate-400">Harga promo</p>
                        <p class="text-2xl font-semibold text-slate-900" x-text="formatCurrency(detailProduct?.price || 0)"></p>
                        <p class="text-xs text-slate-400 line-through" x-show="detailProduct?.price_before" x-text="detailProduct?.price_before ? formatCurrency(detailProduct.price_before) : ''"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs text-slate-400">Diskon</p>
                        <p class="text-lg font-semibold text-cyan-600" x-text="detailProduct?.discount ? detailProduct.discount + '%' : '—'"></p>
                        <p class="text-xs text-slate-500">Stok <span class="font-semibold text-slate-900" x-text="detailProduct?.stock ?? 0"></span></p>
                    </div>
                </div>
                <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                    <button type="button" class="flex-1 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600" @click="detailProduct && addToCart(detailProduct, $event)">
                        Masukkan keranjang
                    </button>
                    <button type="button" class="flex-1 rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:border-cyan-400 hover:text-slate-900" @click="closeProductDetail">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

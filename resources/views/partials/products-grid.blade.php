@php
    $productCount = $productCount ?? $product->total();
@endphp

<section class="mt-12 space-y-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Etalase produk</p>
            <h2 class="text-3xl font-semibold text-slate-900">Pilihan terbaik untuk pelanggan setia.</h2>
            <p class="text-sm text-slate-500">
                Menampilkan {{ $product->count() }} dari {{ number_format($productCount) }} produk yang tersedia.
                @if (!empty($search))
                    <span class="font-semibold text-slate-900">Hasil untuk “{{ $search }}”.</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3 text-xs text-slate-500">
            <div class="rounded-full border border-slate-200 px-3 py-1">Diskon: {{ $activeDiscount }}%</div>
            <div class="rounded-full border border-slate-200 px-3 py-1">Sortir: Terbaru</div>
        </div>
    </div>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($product as $item)
            @if ($item->is_enabled)
                @php
                    $discountedPrice = $item->price - (($item->price * $activeDiscount) / 100);
                    $finalPrice = max($discountedPrice, 0);
                    $imageUrl = $item->getFirstMediaUrl('product_image') ?: asset('default.png');
                    $cartPayload = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $finalPrice,
                        'image' => $imageUrl,
                        'quantity' => 1,
                    ];
                    $detailPayload = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'category' => optional($item->category)->name ?? 'Tanpa kategori',
                        'price' => $finalPrice,
                        'price_before' => $item->price,
                        'discount' => $activeDiscount,
                        'stock' => $item->stock,
                        'image' => $imageUrl,
                        'quantity' => 1,
                    ];
                @endphp
                <article class="glass-panel relative group flex h-full flex-col overflow-hidden border border-slate-100 bg-white">
                    <div class="relative h-56 w-full overflow-hidden">
                        @if ($item->getFirstMediaUrl('product_image'))
                            <img src="{{ $item->getFirstMediaUrl('product_image') }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110" />
                        @else
                            <img src="{{ asset('default.png') }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                        @endif
                        <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-cyan-600">
                            {{ optional($item->category)->name ?? 'Tanpa kategori' }}
                        </div>
                        <div class="absolute right-4 top-4 rounded-full bg-slate-900/90 px-3 py-1 text-xs font-semibold text-white">
                            Stok {{ $item->stock }}
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col px-6 pb-6 pt-5">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="text-xl font-semibold text-slate-900">{{ $item->name }}</h3>
                            @if ($activeDiscount)
                                <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-600">-{{ $activeDiscount }}%</span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->description, 120) }}</p>
                        <div class="mt-auto pt-6">
                            <div class="flex items-baseline justify-between gap-3">
                                <div>
                                    <p class="text-xl font-semibold text-slate-900">{{ formatCurrency($finalPrice) }}</p>
                                    <p class="text-[11px] text-slate-400 line-through">{{ formatCurrency($item->price) }}</p>
                                </div>
                                <span class="rounded-full border border-slate-200 px-3 py-1 text-[11px] text-slate-500">Stok {{ $item->stock }}</span>
                            </div>
                            <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                <button type="button" @click='addToCart(@json($cartPayload))' class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Masukkan keranjang</button>
                                <button type="button" @click='showProductDetail(@json($detailPayload))' class="flex-1 rounded-full bg-slate-900 px-4 py-2 text-center text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-cyan-600">Detail produk</button>
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="glass-panel col-span-full flex flex-col items-center justify-center gap-3 px-6 py-16 text-center">
                <p class="text-2xl font-semibold text-slate-900">Produk belum tersedia</p>
                <p class="text-sm text-slate-500">Tambahkan produk ke katalog untuk menampilkannya di halaman ini.</p>
            </div>
        @endforelse
    </div>

    @if ($product->hasPages())
        <div class="mt-10 flex justify-center">
            <nav class="flex items-center gap-2 text-sm" aria-label="Pagination">
                @if ($product->onFirstPage())
                    <span class="rounded-full border border-slate-100 px-4 py-2 text-slate-300">Sebelumnya</span>
                @else
                    <a href="{{ $product->previousPageUrl() }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Sebelumnya</a>
                @endif

                @foreach ($product->getUrlRange(max(1, $product->currentPage() - 2), min($product->lastPage(), $product->currentPage() + 2)) as $page => $url)
                    @if ($page == $product->currentPage())
                        <span class="rounded-full bg-slate-900 px-4 py-2 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($product->hasMorePages())
                    <a href="{{ $product->nextPageUrl() }}" class="rounded-full border border-slate-200 px-4 py-2 text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">Berikutnya</a>
                @else
                    <span class="rounded-full border border-slate-100 px-4 py-2 text-slate-300">Berikutnya</span>
                @endif
            </nav>
        </div>
    @endif
</section>

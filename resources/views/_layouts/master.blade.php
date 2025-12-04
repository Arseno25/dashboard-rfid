<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="referrer" content="always">
    <meta name="description" content="Zarly Petshop Dashboard - curated inventory & checkout experience" />

    <title>{{ config('app.name', 'ZARLY PETSHOP') }} • Retail Experience</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div x-data="cartUI()" class="relative flex min-h-screen flex-col overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 z-0 h-72 bg-gradient-to-b from-cyan-100/80 via-transparent to-transparent"></div>
        <div class="pointer-events-none absolute inset-y-0 left-1/2 z-0 hidden w-[600px] -translate-x-1/2 rounded-full bg-cyan-200/20 blur-[120px] lg:block"></div>

        <div class="relative z-10 flex flex-1 flex-col">
            @include('_layouts._navbar')

            @include('_layouts._cart')

            @include('_layouts._product-modal')

            <main class="flex-1">
                <div class="mx-auto w-full max-w-6xl px-6 py-10 lg:px-8">
                    @yield('body')
                </div>
            </main>

            @include('_layouts._footer')
        </div>
    </div>

    @stack('scripts')
    <script>
        window.cartUI = function () {
            return {
                cartOpen: false,
                mobileNavOpen: false,
                cartItems: [],
                cartNotice: null,
                cartNoticeType: 'info',
                detailModalOpen: false,
                detailProduct: null,
                addToCart(product) {
                    if (!product) return;
                    const existing = this.cartItems.find(item => item.id === product.id);
                    if (existing) {
                        existing.quantity += product.quantity || 1;
                    } else {
                        this.cartItems.push({
                            ...product,
                            quantity: product.quantity || 1,
                        });
                    }
                    this.cartNoticeType = 'success';
                    this.cartNotice = `${product.name ?? 'Produk'} ditambahkan ke keranjang.`;
                    this.cartOpen = true;
                    if (this.detailModalOpen) {
                        this.closeProductDetail();
                    }
                },
                removeItem(index) {
                    if (index < 0) return;
                    this.cartItems.splice(index, 1);
                    this.cartNoticeType = this.cartItems.length ? 'info' : 'warning';
                    this.cartNotice = this.cartItems.length ? 'Produk dihapus dari keranjang.' : 'Keranjang kini kosong.';
                },
                cartSubtotal() {
                    return this.cartItems.reduce((total, item) => {
                        const qty = item.quantity ?? 1;
                        const price = item.price ?? 0;
                        return total + qty * price;
                    }, 0);
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(value);
                },
                proceedCheckout() {
                    if (!this.cartItems.length) {
                        this.cartNoticeType = 'error';
                        this.cartNotice = 'Keranjang masih kosong. Tambahkan produk terlebih dahulu.';
                        this.cartOpen = true;
                        return;
                    }
                    const totalQty = this.cartItems.reduce((sum, item) => sum + (item.quantity ?? 1), 0);
                    this.cartNoticeType = 'success';
                    this.cartNotice = `${totalQty} produk siap diproses. Silakan lanjutkan pembayaran.`;
                },
                showProductDetail(product) {
                    this.detailProduct = {
                        ...product,
                        quantity: product.quantity || 1,
                    };
                    this.detailModalOpen = true;
                },
                closeProductDetail() {
                    this.detailModalOpen = false;
                    this.detailProduct = null;
                },
            };
        };
    </script>
</body>

</html>

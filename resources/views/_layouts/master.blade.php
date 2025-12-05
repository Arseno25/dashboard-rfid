<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <div x-data="cartUI()" x-init="init()" class="relative flex min-h-screen flex-col overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 z-0 h-72 bg-gradient-to-b from-cyan-100/80 via-transparent to-transparent"></div>
        <div class="pointer-events-none absolute inset-y-0 left-1/2 z-0 hidden w-[600px] -translate-x-1/2 rounded-full bg-cyan-200/20 blur-[120px] lg:block"></div>

        <div class="relative z-10 flex flex-1 flex-col">
            @include('_layouts._navbar')

            @include('_layouts._cart')

            @include('_layouts._product-modal')

            @include('_layouts._payment-method-modal')

            <main class="flex-1">
                <div class="mx-auto w-full max-w-6xl px-6 py-10 lg:px-8">
                    @yield('body')
                </div>
            </main>

            @include('_layouts._footer')
        </div>
    </div>

    <div aria-live="polite" class="pointer-events-none fixed top-6 right-6 z-[200] flex max-w-sm flex-col gap-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="pointer-events-auto rounded-2xl border px-4 py-3 shadow-xl shadow-slate-900/10" :class="{
                    'border-emerald-200 bg-white text-emerald-700': toast.type === 'success',
                    'border-amber-200 bg-white text-amber-700': toast.type === 'warning',
                    'border-rose-200 bg-white text-rose-700': toast.type === 'error',
                    'border-slate-200 bg-white text-slate-700': !['success','warning','error'].includes(toast.type)
                }">
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="text-sm font-semibold" x-text="toast.message"></p>
                    </div>
                    <button type="button" class="text-xs font-semibold" @click="dismissToast(toast.id)">Tutup</button>
                </div>
            </div>
        </template>
    </div>

    @stack('scripts')

    @if (config('services.midtrans.client_key'))
        <script src="{{ config('services.midtrans.snap_url') ?? (config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ config('services.midtrans.client_key') }}" defer></script>
    @endif

    <script>
        window.cartUI = function () {
            return {
                cartOpen: false,
                mobileNavOpen: false,
                cartItems: [],
                detailModalOpen: false,
                detailProduct: null,
                paymentMethodModalOpen: false,
                checkoutForm: {
                    name: '',
                    email: '',
                    phone: '',
                    note: '',
                },
                isAuthenticated: @json(auth()->check()),
                userProfile: @json(optional(auth()->user())->only(['name', 'email', 'phone'])),
                loginUrl: @json(route('login')),
                profileUrl: @json(route('profile.edit')),
                midtransProcessing: false,
                checkoutEndpoint: @json(url('/api/checkout')),
                orderStatusEndpoint: @json(url('/api/orders')),
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                toasts: [],
                toastIncrement: 0,
                cartStorageKey: 'zarly-cart-items',
                pendingCartIntentKey: 'zarly-pending-cart-intent',
                pendingCartIntentTtlMs: 10 * 60 * 1000,
                storageReady: null,
                init() {
                    this.loadCartFromStorage();
                    this.prefillCustomerProfile();
                    this.applyPendingCartIntent();
                },
                addToCart(product, event = null) {
                    const normalizedProduct = this.normalizeCartItem(product);
                    if (!normalizedProduct) {
                        return;
                    }

                    if (!this.isAuthenticated) {
                        const redirectPath = this.getCurrentPath();
                        this.storePendingCartIntent(normalizedProduct, redirectPath);
                        this.pushToast('Silakan masuk untuk menambahkan produk ke keranjang.', 'warning');
                        window.location.href = this.buildLoginRedirectUrl(redirectPath);
                        return;
                    }

                    this.appendCartItem(normalizedProduct);
                    const animationImage = normalizedProduct?.image || product?.image || null;
                    this.animateCartFly(event, animationImage);
                    if (this.detailModalOpen) {
                        this.closeProductDetail();
                    }
                },
                appendCartItem(product, { silent = false } = {}) {
                    const normalized = this.normalizeCartItem(product);
                    if (!normalized) {
                        return;
                    }
                    const existing = this.cartItems.find(item => item.id === normalized.id);
                    if (existing) {
                        existing.quantity += normalized.quantity ?? 1;
                    } else {
                        this.cartItems.push(normalized);
                    }
                    this.persistCart();
                    if (!silent) {
                        this.pushToast(`${normalized.name ?? 'Produk'} ditambahkan ke keranjang.`, 'success');
                    }
                },
                animateCartFly(event, imageUrl = null) {
                    if (typeof document === 'undefined' || typeof window === 'undefined' || !event) {
                        return;
                    }
                    const cartTarget = this.getVisibleCartTarget();
                    const triggerElement = event.currentTarget || event.target;
                    if (!cartTarget || !triggerElement) {
                        return;
                    }
                    const cardElement = typeof triggerElement.closest === 'function'
                        ? triggerElement.closest('[data-product-card]')
                        : null;
                    const mediaElement = cardElement?.querySelector?.('[data-product-image]');
                    const referenceElement = mediaElement || cardElement || triggerElement;
                    const originRect = referenceElement?.getBoundingClientRect?.();
                    const targetRect = cartTarget.getBoundingClientRect?.();
                    if (!originRect || !targetRect) {
                        return;
                    }
                    const flyer = document.createElement('div');
                    flyer.setAttribute('aria-hidden', 'true');
                    flyer.className = 'pointer-events-none fixed z-[140] shadow-2xl shadow-slate-900/20 ring-1 ring-white/50';
                    flyer.style.left = `${originRect.left}px`;
                    flyer.style.top = `${originRect.top}px`;
                    flyer.style.width = `${originRect.width}px`;
                    flyer.style.height = `${originRect.height}px`;
                    flyer.style.borderRadius = '28px';
                    flyer.style.background = imageUrl ? `url("${imageUrl}") center/cover no-repeat` : 'linear-gradient(135deg, #06b6d4, #0f172a)';
                    flyer.style.opacity = '0.95';
                    flyer.style.transform = 'scale(1)';
                    flyer.style.transition = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.7s ease-out';
                    flyer.style.willChange = 'transform, opacity';
                    document.body.appendChild(flyer);

                    requestAnimationFrame(() => {
                        const deltaX = (targetRect.left + targetRect.width / 2) - (originRect.left + originRect.width / 2);
                        const deltaY = (targetRect.top + targetRect.height / 2) - (originRect.top + originRect.height / 2);
                        flyer.style.transform = `translate(${deltaX}px, ${deltaY}px) scale(0.15)`;
                        flyer.style.opacity = '0';
                    });

                    const cleanup = () => {
                        flyer.remove();
                    };
                    flyer.addEventListener('transitionend', cleanup, { once: true });
                    setTimeout(() => {
                        if (document.body.contains(flyer)) {
                            cleanup();
                        }
                    }, 900);
                },
                getVisibleCartTarget() {
                    if (typeof document === 'undefined' || typeof window === 'undefined') {
                        return null;
                    }
                    const targets = document.querySelectorAll('[data-cart-target]');
                    for (const target of targets) {
                        const rect = target.getBoundingClientRect?.();
                        if (!rect || !rect.width || !rect.height) {
                            continue;
                        }
                        const styles = window.getComputedStyle(target);
                        if (styles.display === 'none' || styles.visibility === 'hidden' || styles.opacity === '0') {
                            continue;
                        }
                        return target;
                    }
                    return targets[0] || null;
                },
                removeItem(index) {
                    if (index < 0) return;
                    const [removed] = this.cartItems.splice(index, 1);
                    this.pushToast(removed ? `${removed.name ?? 'Produk'} dihapus dari keranjang.` : 'Produk dihapus dari keranjang.', this.cartItems.length ? 'warning' : 'info');
                    this.persistCart();
                },
                cartSubtotal() {
                    return this.cartItems.reduce((total, item) => {
                        const qty = item.quantity ?? 1;
                        const price = item.price ?? 0;
                        return total + qty * price;
                    }, 0);
                },
                cartItemCount() {
                    return this.cartItems.reduce((total, item) => total + (item.quantity ?? 1), 0);
                },
                canSubmitMidtrans() {
                    return this.cartItems.length > 0
                        && this.isAuthenticated
                        && Boolean(this.checkoutForm.name)
                        && Boolean(this.checkoutForm.email)
                        && Boolean(this.checkoutForm.phone);
                },
                updateItemQuantity(index, delta) {
                    if (typeof index !== 'number') {
                        return;
                    }
                    const item = this.cartItems[index];
                    if (!item) {
                        return;
                    }
                    const currentQty = item.quantity ?? 1;
                    const nextQty = Math.max(1, currentQty + delta);
                    item.quantity = nextQty;
                    this.persistCart();
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(value);
                },
                proceedCheckout() {
                    if (!this.isAuthenticated) {
                        this.pushToast('Silakan masuk terlebih dahulu sebelum checkout.', 'warning');
                        window.location.href = this.loginUrl;
                        return;
                    }
                    if (!this.cartItems.length) {
                        this.pushToast('Keranjang masih kosong. Tambahkan produk terlebih dahulu.', 'error');
                        this.cartOpen = true;
                        return;
                    }
                    this.cartOpen = false;
                    this.openPaymentMethodModal();
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
                openPaymentMethodModal() {
                    this.paymentMethodModalOpen = true;
                },
                closePaymentMethodModal(force = false) {
                    if (this.midtransProcessing && !force) {
                        return;
                    }
                    this.paymentMethodModalOpen = false;
                },
                validateCheckoutForm() {
                    if (!this.cartItems.length) {
                        this.pushToast('Keranjang masih kosong.', 'error');
                        return false;
                    }
                    if (!this.isAuthenticated) {
                        this.pushToast('Masuk dengan akun Anda sebelum checkout.', 'error');
                        return false;
                    }
                    if (!this.checkoutForm.name || !this.checkoutForm.email || !this.checkoutForm.phone) {
                        this.pushToast('Lengkapi data profil (nama, email, dan telepon) di menu profil.', 'error');
                        return false;
                    }
                    return true;
                },
                async startMidtransCheckout() {
                    if (!this.validateCheckoutForm()) {
                        return;
                    }
                    if (!window.snap) {
                        this.pushToast('Halaman pembayaran belum siap. Muat ulang terlebih dahulu.', 'error');
                        return;
                    }
                    this.midtransProcessing = true;
                    try {
                        const payload = {
                            method: 'midtrans',
                            customer: {
                                name: this.checkoutForm.name,
                                email: this.checkoutForm.email,
                                phone: this.checkoutForm.phone,
                                note: this.checkoutForm.note,
                            },
                            cart: this.cartItems.map(item => ({
                                id: item.id,
                                quantity: item.quantity ?? 1,
                            })),
                        };

                        const response = await fetch(this.checkoutEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify(payload),
                        });

                        if (!response.ok) {
                            const error = await response.json().catch(() => ({}));
                            throw new Error(error.message || 'Gagal memulai pembayaran.');
                        }

                        const data = await response.json();
                        this.closePaymentMethodModal(true);

                        window.snap.pay(data.snap_token, {
                            onSuccess: () => this.handleMidtransResult(data.order.order_number, 'success'),
                            onPending: () => this.handleMidtransResult(data.order.order_number, 'pending'),
                            onError: () => this.pushToast('Pembayaran gagal diproses.', 'error'),
                            onClose: () => this.pushToast('Jendela pembayaran ditutup sebelum selesai.', 'warning'),
                        });
                    } catch (error) {
                        this.pushToast(error?.message || 'Gagal memulai pembayaran.', 'error');
                    } finally {
                        this.midtransProcessing = false;
                    }
                },
                async handleMidtransResult(orderNumber, status) {
                    if (status === 'success') {
                        this.pushToast('Pembayaran berhasil dan menunggu konfirmasi sistem.', 'success');
                    } else {
                        this.pushToast('Pembayaran tertunda. Silakan selesaikan proses pada jendela pembayaran.', 'info');
                    }
                    this.cartItems = [];
                    this.clearCartStorage();
                    this.resetCheckoutForm();
                    this.trackOrderStatus(orderNumber, 0);
                },
                async trackOrderStatus(orderNumber, attempt = 0) {
                    try {
                        const response = await fetch(this.getOrderStatusUrl(orderNumber));
                        if (!response.ok) {
                            throw new Error('Tidak dapat memeriksa status order.');
                        }
                        const data = await response.json();
                        if (data.payment_status === 'paid') {
                            this.pushToast('Pembayaran telah dikonfirmasi.', 'success');
                        } else if (data.payment_status === 'failed') {
                            this.pushToast('Pembayaran gagal. Silakan coba metode lain.', 'error');
                        } else if (attempt < 5) {
                            setTimeout(() => this.trackOrderStatus(orderNumber, attempt + 1), 4000);
                        }
                    } catch (error) {
                        if (attempt < 2) {
                            setTimeout(() => this.trackOrderStatus(orderNumber, attempt + 1), 5000);
                        } else {
                            this.pushToast('Gagal memeriksa status pembayaran.', 'warning');
                        }
                    }
                },
                getOrderStatusUrl(orderNumber) {
                    return `${this.orderStatusEndpoint.replace(/\/$/, '')}/${orderNumber}/status`;
                },
                resetCheckoutForm() {
                    this.checkoutForm.note = '';
                    this.prefillCustomerProfile();
                },
                pushToast(message, type = 'info') {
                    const id = ++this.toastIncrement;
                    this.toasts.push({ id, message, type });
                    setTimeout(() => this.dismissToast(id), 5000);
                },
                dismissToast(id) {
                    this.toasts = this.toasts.filter(toast => toast.id !== id);
                },
                storageAvailable() {
                    if (this.storageReady !== null) {
                        return this.storageReady;
                    }
                    if (typeof window === 'undefined' || !('localStorage' in window)) {
                        this.storageReady = false;
                        return this.storageReady;
                    }
                    try {
                        const testKey = '__zarly_cart_check__';
                        window.localStorage.setItem(testKey, '1');
                        window.localStorage.removeItem(testKey);
                        this.storageReady = true;
                    } catch (error) {
                        this.storageReady = false;
                    }
                    return this.storageReady;
                },
                normalizeCartItem(raw) {
                    if (!raw || typeof raw !== 'object') {
                        return null;
                    }
                    const quantity = Math.max(1, Number(raw.quantity) || 1);
                    return {
                        ...raw,
                        quantity,
                    };
                },
                loadCartFromStorage() {
                    if (!this.storageAvailable()) {
                        return;
                    }
                    try {
                        const cached = window.localStorage.getItem(this.cartStorageKey);
                        if (!cached) {
                            return;
                        }
                        const parsed = JSON.parse(cached);
                        if (!Array.isArray(parsed)) {
                            return;
                        }
                        const restored = parsed
                            .map(item => this.normalizeCartItem(item))
                            .filter(Boolean);
                        if (restored.length) {
                            this.cartItems = restored;
                        }
                    } catch (error) {
                        console.warn('Gagal memuat keranjang dari penyimpanan.', error);
                    }
                },
                persistCart() {
                    if (!this.storageAvailable()) {
                        return;
                    }
                    try {
                        if (!this.cartItems.length) {
                            this.clearCartStorage();
                            return;
                        }
                        const payload = this.cartItems
                            .map(item => this.normalizeCartItem(item))
                            .filter(Boolean);
                        window.localStorage.setItem(this.cartStorageKey, JSON.stringify(payload));
                    } catch (error) {
                        console.warn('Gagal menyimpan keranjang.', error);
                    }
                },
                clearCartStorage() {
                    if (!this.storageAvailable()) {
                        return;
                    }
                    window.localStorage.removeItem(this.cartStorageKey);
                },
                storePendingCartIntent(product, redirectPath = null) {
                    if (!this.storageAvailable()) {
                        return;
                    }
                    try {
                        const payload = {
                            product,
                            redirect: redirectPath && redirectPath.startsWith('/') ? redirectPath : this.getCurrentPath(),
                            stored_at: Date.now(),
                        };
                        window.localStorage.setItem(this.pendingCartIntentKey, JSON.stringify(payload));
                    } catch (error) {
                        console.warn('Gagal menyimpan niat keranjang.', error);
                    }
                },
                readPendingCartIntent() {
                    if (!this.storageAvailable()) {
                        return null;
                    }
                    try {
                        const raw = window.localStorage.getItem(this.pendingCartIntentKey);
                        if (!raw) {
                            return null;
                        }
                        const parsed = JSON.parse(raw);
                        if (!parsed || typeof parsed !== 'object') {
                            return null;
                        }
                        const storedAt = typeof parsed.stored_at === 'number' ? parsed.stored_at : null;
                        if (storedAt && Date.now() - storedAt > this.pendingCartIntentTtlMs) {
                            this.clearPendingCartIntent();
                            return null;
                        }
                        const normalizedProduct = this.normalizeCartItem(parsed.product);
                        if (!normalizedProduct) {
                            return null;
                        }
                        return {
                            product: normalizedProduct,
                            redirect: typeof parsed.redirect === 'string' ? parsed.redirect : null,
                        };
                    } catch (error) {
                        console.warn('Gagal membaca niat keranjang.', error);
                        return null;
                    }
                },
                clearPendingCartIntent() {
                    if (!this.storageAvailable()) {
                        return;
                    }
                    window.localStorage.removeItem(this.pendingCartIntentKey);
                },
                applyPendingCartIntent() {
                    if (!this.isAuthenticated) {
                        return;
                    }
                    const intent = this.readPendingCartIntent();
                    if (!intent || !intent.product) {
                        return;
                    }
                    this.clearPendingCartIntent();
                    this.appendCartItem(intent.product, { silent: true });
                    this.pushToast(`${intent.product.name ?? 'Produk'} dimasukkan ke keranjang setelah login.`, 'success');
                },
                prefillCustomerProfile() {
                    if (!this.isAuthenticated || !this.userProfile) {
                        this.checkoutForm.name = '';
                        this.checkoutForm.email = '';
                        this.checkoutForm.phone = '';
                        return;
                    }
                    this.checkoutForm.name = this.userProfile.name ?? '';
                    this.checkoutForm.email = this.userProfile.email ?? '';
                    this.checkoutForm.phone = this.userProfile.phone ?? '';
                },
                buildLoginRedirectUrl(targetPath = null) {
                    const redirectPath = typeof targetPath === 'string' && targetPath.startsWith('/')
                        ? targetPath
                        : this.getCurrentPath();
                    if (!redirectPath) {
                        return this.loginUrl;
                    }
                    try {
                        const loginUrl = new URL(this.loginUrl, window.location.origin);
                        loginUrl.searchParams.set('redirect', redirectPath);
                        return loginUrl.toString();
                    } catch (error) {
                        const separator = this.loginUrl.includes('?') ? '&' : '?';
                        return `${this.loginUrl}${separator}redirect=${encodeURIComponent(redirectPath)}`;
                    }
                },
                getCurrentPath() {
                    if (typeof window === 'undefined' || !window.location) {
                        return '/';
                    }
                    const { pathname = '/', search = '', hash = '' } = window.location;
                    return `${pathname}${search}${hash}` || '/';
                },
            };
        };
    </script>
</body>

</html>

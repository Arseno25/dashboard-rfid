<footer id="contact" class="relative z-20 mt-12 border-t border-white/40 bg-white/85 shadow-inner backdrop-blur dark:border-white/10 dark:bg-slate-950/80 dark:shadow-none">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-6 py-10 text-sm text-slate-500 md:flex-row md:justify-between lg:px-8 dark:text-slate-300">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400 dark:text-slate-500">Zarly Petshop</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">Retail Intelligence</p>
            <p class="mt-3 max-w-sm text-slate-500 dark:text-slate-300">Monitoring stok, diskon, dan penjualan hewan peliharaan Anda dalam satu dashboard modern berbasis RFID.</p>
        </div>
        <div class="flex flex-1 flex-col gap-4 md:flex-row md:justify-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Navigasi</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-slate-900 dark:hover:text-white">Etalase Produk</a></li>
                    <li><a href="{{ route('category') }}" class="transition hover:text-slate-900 dark:hover:text-white">Kategori</a></li>
                    <li><a href="#insight" class="transition hover:text-slate-900 dark:hover:text-white">Insight</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Kontak</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="mailto:hello@zarlypetshop.id" class="transition hover:text-slate-900 dark:hover:text-white">hello@zarlypetshop.id</a></li>
                    <li><a href="tel:+628123456789" class="transition hover:text-slate-900 dark:hover:text-white">+62 812 3456 789</a></li>
                    <li><span class="text-slate-500 dark:text-slate-300">Jl. Halimun No. 17, Jakarta</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100/60 text-center text-xs text-slate-400 dark:border-white/5 dark:text-slate-500">
        <p class="py-4">© {{ now()->year }} Zarly Petshop. All rights reserved.</p>
    </div>
</footer>

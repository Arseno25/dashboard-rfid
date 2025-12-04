<footer id="contact" class="relative z-20 mt-12 border-t border-white/40 bg-white/80 backdrop-blur">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-6 py-10 text-sm text-slate-500 md:flex-row md:justify-between lg:px-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Zarly Petshop</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">Retail Intelligence</p>
            <p class="mt-3 max-w-sm text-slate-500">Monitoring stok, diskon, dan penjualan hewan peliharaan Anda dalam satu dashboard modern berbasis RFID.</p>
        </div>
        <div class="flex flex-1 flex-col gap-4 md:flex-row md:justify-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Navigasi</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-slate-900">Etalase Produk</a></li>
                    <li><a href="{{ route('category') }}" class="hover:text-slate-900">Kategori</a></li>
                    <li><a href="#insight" class="hover:text-slate-900">Insight</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Kontak</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="mailto:hello@zarlypetshop.id" class="hover:text-slate-900">hello@zarlypetshop.id</a></li>
                    <li><a href="tel:+628123456789" class="hover:text-slate-900">+62 812 3456 789</a></li>
                    <li><span>Jl. Halimun No. 17, Jakarta</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100/60 text-center text-xs text-slate-400">
        <p class="py-4">© {{ now()->year }} Zarly Petshop. All rights reserved.</p>
    </div>
</footer>

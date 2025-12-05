@php
    $siteName = $siteSettings['name'] ?? 'Zarly Petshop';
    $siteTagline = $siteSettings['tagline'] ?? 'Retail Intelligence';
    $heroSubtitle = $siteSettings['hero_subtitle'] ?? 'Monitoring stok, diskon, dan penjualan hewan peliharaan Anda dalam satu dashboard modern berbasis RFID.';
    $contactEmail = $siteSettings['contact_email'] ?? 'hello@zarlypetshop.id';
    $contactPhone = $siteSettings['contact_phone'] ?? '+62 812 3456 789';
    $contactAddress = $siteSettings['contact_address'] ?? 'Jl. Halimun No. 17, Jakarta';
    $contactWhatsapp = $siteSettings['contact_whatsapp'] ?? null;
@endphp

<footer id="contact" class="relative z-20 mt-12 border-t border-white/40 bg-white/85 shadow-inner backdrop-blur dark:border-white/10 dark:bg-slate-950/80 dark:shadow-none">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-6 py-10 text-sm text-slate-500 md:flex-row md:justify-between lg:px-8 dark:text-slate-300">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400 dark:text-slate-500">{{ $siteName }}</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ $siteTagline }}</p>
            <p class="mt-3 max-w-sm text-slate-500 dark:text-slate-300">{{ $heroSubtitle }}</p>
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
                    <li><a href="mailto:{{ $contactEmail }}" class="transition hover:text-slate-900 dark:hover:text-white">{{ $contactEmail }}</a></li>
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}" class="transition hover:text-slate-900 dark:hover:text-white">{{ $contactPhone }}</a></li>
                    @if ($contactWhatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/\D+/', '', $contactWhatsapp) }}" class="transition hover:text-slate-900 dark:hover:text-white">WhatsApp: {{ $contactWhatsapp }}</a></li>
                    @endif
                    <li><span class="text-slate-500 dark:text-slate-300">{{ $contactAddress }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100/60 text-center text-xs text-slate-400 dark:border-white/5 dark:text-slate-500">
        <p class="py-4">© {{ now()->year }} {{ $siteName }}. All rights reserved.</p>
    </div>
</footer>

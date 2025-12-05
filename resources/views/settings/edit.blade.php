@php
    use Illuminate\Support\Facades\Storage;
    $value = fn(string $key, $default = null) => $settings[$key] ?? $default;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold leading-tight text-slate-900 dark:text-white">Pengaturan Situs</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Sesuaikan identitas brand, kontak, dan metadata website Anda.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-400/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" x-data="{ tab: 'general' }" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-wrap gap-3 rounded-3xl border border-slate-100 bg-white/80 p-3 shadow-inner shadow-slate-100 dark:border-white/10 dark:bg-slate-900/60 dark:shadow-black/20">
                    <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-slate-900 text-white dark:bg-white/10 dark:text-white' : 'text-slate-600 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Informasi Umum</button>
                    <button type="button" @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-slate-900 text-white dark:bg-white/10 dark:text-white' : 'text-slate-600 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Branding</button>
                    <button type="button" @click="tab = 'contact'" :class="tab === 'contact' ? 'bg-slate-900 text-white dark:bg-white/10 dark:text-white' : 'text-slate-600 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Kontak</button>
                    <button type="button" @click="tab = 'meta'" :class="tab === 'meta' ? 'bg-slate-900 text-white dark:bg-white/10 dark:text-white' : 'text-slate-600 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Metadata</button>
                </div>

                <section x-show="tab === 'general'" x-transition class="glass-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Umum</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Atur nama situs dan pesan utama yang ditampilkan ke pengunjung.</p>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="general_site_name" value="Nama Situs" />
                            <x-text-input id="general_site_name" name="general_site_name" type="text" class="mt-1 block w-full" value="{{ old('general_site_name', $value('general_site_name', config('app.name', 'Zarly Petshop'))) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('general_site_name')" />
                        </div>
                        <div>
                            <x-input-label for="general_tagline" value="Tagline" />
                            <x-text-input id="general_tagline" name="general_tagline" type="text" class="mt-1 block w-full" value="{{ old('general_tagline', $value('general_tagline')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('general_tagline')" />
                        </div>
                        <div>
                            <x-input-label for="general_homepage_title" value="Judul Hero" />
                            <textarea id="general_homepage_title" name="general_homepage_title" rows="2" class="mt-1 block w-full rounded-2xl border-slate-200 bg-white/90 text-sm text-slate-900 shadow-inner shadow-slate-100 focus:border-cyan-400 focus:ring-cyan-400 dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-100">{{ old('general_homepage_title', $value('general_homepage_title')) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('general_homepage_title')" />
                        </div>
                        <div>
                            <x-input-label for="general_homepage_subtitle" value="Subjudul Hero" />
                            <textarea id="general_homepage_subtitle" name="general_homepage_subtitle" rows="2" class="mt-1 block w-full rounded-2xl border-slate-200 bg-white/90 text-sm text-slate-900 shadow-inner shadow-slate-100 focus:border-cyan-400 focus:ring-cyan-400 dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-100">{{ old('general_homepage_subtitle', $value('general_homepage_subtitle')) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('general_homepage_subtitle')" />
                        </div>
                    </div>
                </section>

                <section x-show="tab === 'branding'" x-transition x-cloak class="glass-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Branding</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Kelola aset visual untuk memastikan konsistensi brand.</p>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="brand_primary_color" value="Warna Utama" />
                            <x-text-input id="brand_primary_color" name="brand_primary_color" type="text" class="mt-1 block w-full" placeholder="#0f172a" value="{{ old('brand_primary_color', $value('brand_primary_color')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('brand_primary_color')" />
                        </div>
                        <div>
                            <x-input-label for="brand_accent_color" value="Warna Aksen" />
                            <x-text-input id="brand_accent_color" name="brand_accent_color" type="text" class="mt-1 block w-full" placeholder="#0ea5e9" value="{{ old('brand_accent_color', $value('brand_accent_color')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('brand_accent_color')" />
                        </div>
                        <div>
                            <x-input-label for="brand_logo" value="Logo" />
                            <input id="brand_logo" name="brand_logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-slate-500" />
                            <x-input-error class="mt-2" :messages="$errors->get('brand_logo')" />
                            @if ($value('brand_logo_path'))
                                <div class="mt-3 flex items-center gap-3">
                                    <img src="{{ Storage::url($value('brand_logo_path')) }}" alt="Logo" class="h-12 rounded-xl border border-slate-100 dark:border-white/10" />
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Logo aktif</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <x-input-label for="brand_favicon" value="Favicon" />
                            <input id="brand_favicon" name="brand_favicon" type="file" accept="image/png,image/x-icon" class="mt-1 block w-full text-sm text-slate-500" />
                            <x-input-error class="mt-2" :messages="$errors->get('brand_favicon')" />
                            @if ($value('brand_favicon_path'))
                                <div class="mt-3 flex items-center gap-3">
                                    <img src="{{ Storage::url($value('brand_favicon_path')) }}" alt="Favicon" class="h-8 w-8 rounded border border-slate-100 dark:border-white/10" />
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Favicon aktif</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <section x-show="tab === 'contact'" x-transition x-cloak class="glass-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Kontak & Kanal</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Berikan informasi komunikasi agar pelanggan mudah menjangkau Anda.</p>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="contact_email" value="Email Support" />
                            <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" value="{{ old('contact_email', $value('contact_email')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                        </div>
                        <div>
                            <x-input-label for="contact_phone" value="Nomor Telepon" />
                            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" value="{{ old('contact_phone', $value('contact_phone')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                        </div>
                        <div>
                            <x-input-label for="contact_whatsapp" value="WhatsApp" />
                            <x-text-input id="contact_whatsapp" name="contact_whatsapp" type="text" class="mt-1 block w-full" value="{{ old('contact_whatsapp', $value('contact_whatsapp')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_whatsapp')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="contact_address" value="Alamat" />
                            <textarea id="contact_address" name="contact_address" rows="2" class="mt-1 block w-full rounded-2xl border-slate-200 bg-white/90 text-sm text-slate-900 shadow-inner shadow-slate-100 focus:border-cyan-400 focus:ring-cyan-400 dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-100">{{ old('contact_address', $value('contact_address')) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('contact_address')" />
                        </div>
                    </div>
                </section>

                <section x-show="tab === 'meta'" x-transition x-cloak class="glass-panel p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Metadata</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Optimalkan SEO dengan deskripsi dan kata kunci.</p>
                    <div class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="meta_description" value="Meta Description" />
                            <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-2xl border-slate-200 bg-white/90 text-sm text-slate-900 shadow-inner shadow-slate-100 focus:border-cyan-400 focus:ring-cyan-400 dark:border-white/10 dark:bg-slate-900/60 dark:text-slate-100">{{ old('meta_description', $value('meta_description')) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('meta_description')" />
                        </div>
                        <div>
                            <x-input-label for="meta_keywords" value="Meta Keywords" />
                            <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full" placeholder="mis: petshop, rfid, zarly" value="{{ old('meta_keywords', $value('meta_keywords')) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('meta_keywords')" />
                        </div>
                    </div>
                </section>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center rounded-full border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400 dark:border-white/15 dark:text-slate-200">Batal</a>
                    <button type="submit" class="inline-flex items-center rounded-full bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:bg-cyan-600">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

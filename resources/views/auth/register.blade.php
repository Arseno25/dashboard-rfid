@php
    $siteName = $siteSettings['name'] ?? 'Zarly Petshop';
@endphp

<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Daftar akun</p>
            <h2 class="text-3xl font-semibold text-slate-900">Buat profil pelanggan baru</h2>
            <p class="text-sm text-slate-500">Lengkapi data agar checkout lebih cepat dan setiap pesanan otomatis tersimpan di dasbor Anda.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <x-input-label for="name" value="Nama lengkap" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Citra Larasati" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="phone" value="Nomor telepon" />
                    <x-text-input id="phone" type="text" name="phone" :value="old('phone')" autocomplete="tel" placeholder="0812 3456 7890" />
                    <x-input-error :messages="$errors->get('phone')" />
                </div>
            </div>

            <div class="space-y-2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@pelanggan.com" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <x-input-label for="password" value="Kata sandi" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="password_confirmation" value="Ulangi kata sandi" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Tulis ulang kata sandi" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-500">
                Dengan membuat akun, Anda menyetujui kebijakan privasi {{ $siteName }} dan bersedia menerima email terkait status pesanan.
            </div>

            <x-primary-button class="w-full justify-center">Buat akun baru</x-primary-button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-cyan-600 transition hover:text-cyan-500">Masuk sekarang</a>
        </p>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Buat kata sandi baru</p>
            <h2 class="text-3xl font-semibold text-slate-900">Ayo kunci akunmu lagi</h2>
            <p class="text-sm text-slate-500">Silakan isi data berikut untuk menyelesaikan proses pemulihan kata sandi.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="nama@pelanggan.com" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="Kata sandi baru" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password_confirmation" value="Ulangi kata sandi" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Tulis ulang kata sandi" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <x-primary-button class="w-full justify-center">
                Simpan kata sandi baru
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Masih mengalami kendala? <a href="mailto:hello@zarlypetshop.com" class="font-semibold text-cyan-600 transition hover:text-cyan-500">Hubungi tim kami</a>
        </p>
    </div>
</x-guest-layout>

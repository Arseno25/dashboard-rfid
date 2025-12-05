<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Masuk akun</p>
            <h2 class="text-3xl font-semibold text-slate-900">Selamat datang kembali 👋</h2>
            <p class="text-sm text-slate-500">Gunakan email dan kata sandi untuk mengakses riwayat pesanan, invoice, dan status pembayaran terbaru.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@pelanggan.com" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="Kata sandi" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                <label for="remember_me" class="inline-flex items-center gap-2 text-slate-600">
                    <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500" name="remember">
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a class="font-semibold text-cyan-600 transition hover:text-cyan-500" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full justify-center">
                Masuk sekarang
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Baru di Zarly Petshop?
            <a href="{{ route('register') }}" class="font-semibold text-cyan-600 transition hover:text-cyan-500">Buat akun</a>
        </p>
    </div>
</x-guest-layout>

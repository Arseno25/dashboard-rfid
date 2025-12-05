<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Atur ulang kata sandi</p>
            <h2 class="text-3xl font-semibold text-slate-900">Kami bantu pulihkan akses Anda</h2>
            <p class="text-sm text-slate-500">Masukkan email yang terdaftar. Kami akan mengirim tautan khusus untuk membuat kata sandi baru.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" value="Email terdaftar" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@pelanggan.com" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <x-primary-button class="w-full justify-center">
                Kirim tautan pemulihan
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Ingat kata sandi?
            <a href="{{ route('login') }}" class="font-semibold text-cyan-600 transition hover:text-cyan-500">Kembali ke halaman masuk</a>
        </p>
    </div>
</x-guest-layout>

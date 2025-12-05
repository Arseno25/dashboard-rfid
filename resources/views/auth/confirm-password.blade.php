<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Konfirmasi keamanan</p>
            <h2 class="text-3xl font-semibold text-slate-900">Masukkan kata sandi Anda</h2>
            <p class="text-sm text-slate-500">Untuk melanjutkan tindakan sensitif seperti memperbarui profil atau menghapus akun, kami perlu memastikan bahwa ini benar-benar Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-input-label for="password" value="Kata sandi" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <x-primary-button class="w-full justify-center">
                Konfirmasi dan lanjutkan
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Verifikasi email</p>
            <h2 class="text-3xl font-semibold text-slate-900">Tinggal satu langkah lagi!</h2>
            <p class="text-sm text-slate-500">Kami telah mengirim tautan verifikasi ke alamat email Anda. Klik tautan tersebut untuk mengaktifkan akun sepenuhnya.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-600">
                Tautan verifikasi baru sudah dikirim. Silakan cek inbox atau folder spam Anda.
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                @csrf
                <x-primary-button class="w-full justify-center">Kirim ulang tautan</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-cyan-400 hover:text-slate-900">
                    Keluar dari akun
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500">Belum menerima email? Pastikan alamat email Anda benar atau coba lagi dalam beberapa menit.</p>
    </div>
</x-guest-layout>

@extends('_layouts.account')

@section('body')
<div class="space-y-8">
    <section class="rounded-4xl border border-slate-100 bg-white px-8 py-10 shadow-xl shadow-slate-200/40">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Profil & keamanan</p>
                <h1 class="text-3xl font-semibold text-slate-900">Kelola identitas Anda</h1>
                <p class="max-w-2xl text-sm text-slate-500">Perbarui data kontak, pastikan kata sandi selalu kuat, dan kendalikan akses akun Anda dari satu halaman yang nyaman.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-slate-50 px-6 py-5 text-sm text-slate-600">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Info singkat</p>
                <dl class="mt-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <dt>Email</dt>
                        <dd class="font-semibold text-slate-900">{{ auth()->user()?->email }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Telepon</dt>
                        <dd class="font-semibold text-slate-900">{{ auth()->user()?->phone ?? 'Belum diisi' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            @include('profile.partials.update-profile-information-form')
        </article>

        <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            @include('profile.partials.update-password-form')
        </article>

        <article class="xl:col-span-2 rounded-3xl border border-rose-100 bg-white p-6 shadow-sm">
            @include('profile.partials.delete-user-form')
        </article>
    </section>
</div>
@endsection

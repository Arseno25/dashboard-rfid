<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-200 disabled:opacity-40']) }}>
    {{ $slot }}
</button>

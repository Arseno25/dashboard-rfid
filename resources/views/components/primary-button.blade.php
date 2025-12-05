<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-40']) }}>
    {{ $slot }}
</button>

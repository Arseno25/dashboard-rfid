@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/20 focus:outline-none disabled:bg-slate-100 disabled:text-slate-400']) !!}>

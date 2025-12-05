@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold uppercase tracking-[0.3em] text-slate-500']) }}>
    {{ $value ?? $slot }}
</label>

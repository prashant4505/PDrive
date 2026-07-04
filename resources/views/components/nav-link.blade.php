@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-2xl bg-slate-100 text-sm font-semibold text-slate-950 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-2xl text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

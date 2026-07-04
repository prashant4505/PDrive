@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 text-start text-base font-semibold text-sky-700 bg-sky-50 rounded-2xl transition duration-150 ease-in-out'
            : 'block w-full px-4 py-3 text-start text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

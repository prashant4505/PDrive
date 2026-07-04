@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 bg-slate-50 focus:border-sky-500 focus:ring-sky-500 rounded-2xl shadow-sm text-slate-900 placeholder:text-slate-400']) }}>

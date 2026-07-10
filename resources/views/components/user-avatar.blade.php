@props(['user', 'size' => 'h-7 w-7', 'textSize' => 'text-xs'])

@if ($user->avatarUrl())
    <img
        src="{{ $user->avatarUrl() }}"
        alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "$size shrink-0 rounded-full object-cover"]) }}
    >
@else
    <span {{ $attributes->merge(['class' => "flex $size shrink-0 items-center justify-center rounded-full bg-indigo-600 $textSize font-bold uppercase text-white"]) }}>{{ substr($user->name, 0, 1) }}</span>
@endif

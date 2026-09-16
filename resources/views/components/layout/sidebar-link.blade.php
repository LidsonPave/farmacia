@props(['href', 'active' => false])

@php
$classes = $active
    ? 'flex items-center gap-3 rounded-lg bg-primary-800 px-3 py-2 text-sm font-medium text-white transition'
    : 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-primary-100 transition hover:bg-primary-800/60 hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

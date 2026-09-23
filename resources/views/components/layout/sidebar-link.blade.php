@props(['href', 'active' => false])

@php
$classes = $active
    ? 'flex items-center gap-3 rounded-lg border-l-4 border-white/90 bg-primary-800 py-2.5 pl-2.5 pr-3 text-sm font-medium text-white transition'
    : 'flex items-center gap-3 rounded-lg border-l-4 border-transparent py-2.5 pl-2.5 pr-3 text-sm font-medium text-primary-100 transition hover:bg-primary-800/60 hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

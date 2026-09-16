@props(['status' => 'default'])

@php
$styles = match($status) {
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-amber-100 text-amber-800',
    'danger' => 'bg-red-100 text-red-800',
    'neutral' => 'bg-gray-100 text-gray-700',
    default => 'bg-gray-100 text-gray-700',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium $styles"]) }}>
    {{ $slot }}
</span>

@props(['type' => 'success'])

@php
$config = match($type) {
    'success' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'icon' => 'M4.5 12.75l6 6 9-13.5'],
    'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
    'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
    'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z'],
    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'icon' => 'M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z'],
};
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-2 rounded-lg {$config['bg']} {$config['text']} px-4 py-3 text-sm mb-4"]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5 shrink-0">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
    </svg>
    <span>{{ $slot }}</span>
</div>

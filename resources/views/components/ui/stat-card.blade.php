@props(['status' => 'neutral', 'icon', 'label', 'value', 'context' => null, 'actionLabel' => null, 'actionHref' => null])

@php
$borderColor = match($status) {
    'success' => 'border-l-primary-600',
    'warning' => 'border-l-amber-500',
    'danger' => 'border-l-red-500',
    default => 'border-l-gray-200',
};

$iconWrapper = match($status) {
    'success' => 'bg-primary-100 text-primary-700',
    'warning' => 'bg-amber-100 text-amber-600',
    'danger' => 'bg-red-100 text-red-600',
    default => 'bg-gray-100 text-gray-500',
};

$valueColor = match($status) {
    'warning' => 'text-amber-600',
    'danger' => 'text-red-600',
    default => 'text-gray-900',
};

$actionColor = match($status) {
    'warning' => 'text-amber-700 group-hover:text-amber-800',
    'danger' => 'text-red-700 group-hover:text-red-800',
    default => 'text-primary-700 group-hover:text-primary-800',
};

$baseClasses = "flex items-start gap-4 rounded-xl border border-gray-200 border-l-4 $borderColor bg-white p-5 shadow-sm";
$tag = $actionHref ? 'a' : 'div';
$interactiveClasses = $actionHref ? 'group transition hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500' : '';
@endphp

<{{ $tag }}
    @if($actionHref) href="{{ $actionHref }}" @endif
    {{ $attributes->merge(['class' => "$baseClasses $interactiveClasses"]) }}
>
    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg {{ $iconWrapper }}">
        {{ $icon }}
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="mt-1 text-2xl font-bold {{ $valueColor }}">{{ $value }}</p>
        @if($context)
            <p class="mt-1 text-xs text-gray-400">{{ $context }}</p>
        @endif
        @if($actionLabel && $actionHref)
            <p class="mt-2 text-xs font-medium {{ $actionColor }}">{{ $actionLabel }} →</p>
        @endif
    </div>
</{{ $tag }}>

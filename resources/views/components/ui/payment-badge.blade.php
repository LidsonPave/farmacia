@props(['method'])

@php
$map = [
    'dinheiro' => ['label' => 'Dinheiro', 'class' => 'bg-gray-100 text-gray-700'],
    'mpesa' => ['label' => 'M-Pesa', 'class' => 'bg-primary-100 text-primary-700'],
    'emola' => ['label' => 'e-Mola', 'class' => 'bg-teal-100 text-teal-700'],
    'cartao' => ['label' => 'Cartão', 'class' => 'bg-blue-100 text-blue-700'],
    'outro' => ['label' => 'Outro', 'class' => 'bg-gray-100 text-gray-500'],
];
$config = $map[$method] ?? ['label' => ucfirst($method), 'class' => 'bg-gray-100 text-gray-500'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$config['class']}"]) }}>
    {{ $config['label'] }}
</span>

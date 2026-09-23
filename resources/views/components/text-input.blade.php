@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-gray-300 py-2.5 shadow-sm focus:border-primary-500 focus:ring-primary-500']) }}>

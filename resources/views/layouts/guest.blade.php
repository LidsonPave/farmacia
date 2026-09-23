<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SGF') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-primary-50 px-4 py-10">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-11 w-11 shrink-0 text-primary-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    <circle cx="12" cy="12" r="9" stroke-linecap="round" />
                </svg>
                <div class="leading-tight">
                    <p class="text-xl font-bold text-primary-700">SGF</p>
                    <p class="text-xs text-gray-500">Sistema de Gestão de Farmácia</p>
                </div>
            </div>

            <div class="mt-8 w-full max-w-sm overflow-hidden rounded-xl bg-white px-6 py-8 shadow-lg sm:max-w-md sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

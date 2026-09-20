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
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="app-shell flex h-screen overflow-hidden bg-gray-50">
            <x-layout.sidebar />

            <div class="flex flex-1 flex-col overflow-hidden">
                <x-layout.header />

                @isset($header)
                    <div class="border-b border-gray-200 bg-white px-4 py-4 sm:px-6">
                        {{ $header }}
                    </div>
                @endisset

                <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{ $print ?? '' }}

        @stack('scripts')
    </body>
</html>

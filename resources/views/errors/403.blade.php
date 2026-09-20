<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Acesso Restrito" subtitle="Não tem permissão para aceder a esta área" />
    </x-slot>

    <div class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-8 w-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </div>

        <p class="mt-4 text-lg font-semibold text-gray-900">Esta área é reservada a administradores</p>
        <p class="mt-1 max-w-sm text-sm text-gray-500">
            {{ $exception->getMessage() ?: 'Não tem permissão para aceder a esta página.' }}
        </p>

        <a href="{{ route('dashboard') }}" class="mt-6 rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
            Voltar ao Dashboard
        </a>
    </div>
</x-app-layout>

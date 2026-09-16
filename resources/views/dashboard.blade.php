<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Dashboard" subtitle="Visão geral da farmácia" />
    </x-slot>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-gray-600">
            Layout base instalado com sucesso. Os indicadores e gráficos serão adicionados na próxima fase.
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
            <x-ui.badge status="success">Normal</x-ui.badge>
            <x-ui.badge status="warning">Stock Baixo</x-ui.badge>
            <x-ui.badge status="danger">Expirado</x-ui.badge>
            <x-ui.badge status="neutral">Sem validade</x-ui.badge>
        </div>
    </div>
</x-app-layout>

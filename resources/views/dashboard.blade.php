<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Dashboard" subtitle="Visão geral da farmácia" />
    </x-slot>

    <div class="space-y-8">
        <div>
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Resumo Geral</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Vendas de Hoje</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($salesToday, 2, ',', '.') }} MT</p>
                        @unless($hasAnySale)
                            <p class="mt-1 text-xs text-gray-400">Sem vendas registadas</p>
                        @endunless
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Vendas do Mês</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($salesThisMonth, 2, ',', '.') }} MT</p>
                        @unless($hasAnySale)
                            <p class="mt-1 text-xs text-gray-400">Módulo por implementar</p>
                        @endunless
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Produtos em Stock</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $totalMedicines }}</p>
                        <p class="mt-1 text-xs text-gray-400">Itens ativos</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stock Baixo</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $lowStockCount }}</p>
                        <p class="mt-1 text-xs text-gray-400">Requer atenção</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Alertas de Validade</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Próximos da Validade</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $nearExpiryCount }}</p>
                        <p class="mt-1 text-xs text-gray-400">Dentro de {{ \App\Models\Medicine::EXPIRY_WARNING_DAYS }} dias</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Expirados</p>
                        <p class="mt-1 text-2xl font-bold text-red-600">{{ $expiredCount }}</p>
                        <p class="mt-1 text-xs text-gray-400">Itens vencidos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

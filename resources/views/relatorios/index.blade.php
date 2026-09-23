<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Relatórios" subtitle="Fecho diário e indicadores" />
    </x-slot>

    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('relatorios.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="text-xs font-medium text-gray-500">Data</label>
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-800">
                Filtrar
            </button>
        </form>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Fecho do Dia — {{ $date->format('d/m/Y') }}</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.stat-card status="success" label="Total Vendido" :value="number_format($totalSold, 2, ',', '.') . ' MT'">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>

            <x-ui.stat-card status="neutral" label="Número de Vendas" :value="$totalSalesCount">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.98-4.716 2.545-7.234A1.125 1.125 0 0 0 19.98 5.25H4.5m0 0-.397-1.489m0 0L3.375 3M3.375 3h-.001M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Vendas por Método de Pagamento</h2>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="divide-y divide-gray-100">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-gray-400"></span>
                        <span class="text-sm text-gray-700">Dinheiro</span>
                    </div>
                    <span class="text-base font-semibold text-gray-900">{{ number_format($paymentTotals['dinheiro'], 2, ',', '.') }} MT</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-primary-600"></span>
                        <span class="text-sm text-gray-700">M-Pesa</span>
                    </div>
                    <span class="text-base font-semibold text-gray-900">{{ number_format($paymentTotals['mpesa'], 2, ',', '.') }} MT</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-teal-600"></span>
                        <span class="text-sm text-gray-700">e-Mola</span>
                    </div>
                    <span class="text-base font-semibold text-gray-900">{{ number_format($paymentTotals['emola'], 2, ',', '.') }} MT</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-blue-600"></span>
                        <span class="text-sm text-gray-700">Cartão</span>
                    </div>
                    <span class="text-base font-semibold text-gray-900">{{ number_format($paymentTotals['cartao'], 2, ',', '.') }} MT</span>
                </div>
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-gray-300"></span>
                        <span class="text-sm text-gray-700">Outro</span>
                    </div>
                    <span class="text-base font-semibold text-gray-900">{{ number_format($paymentTotals['outro'], 2, ',', '.') }} MT</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Top 5 Medicamentos Vendidos</h2>

        <div class="rounded-xl border border-gray-200 bg-white p-2 shadow-sm">
            @forelse($topMedicines as $index => $item)
                @php
                    $maxQuantity = $topMedicines->max('total_quantity') ?: 1;
                    $barWidth = ($item->total_quantity / $maxQuantity) * 100;
                @endphp
                <div class="flex items-center gap-4 px-3 py-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-50 text-sm font-bold text-primary-700">
                        {{ $index + 1 }}º
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate text-sm font-medium text-gray-900">{{ $item->medicine->name }}</span>
                            <span class="shrink-0 text-sm font-semibold text-gray-900">{{ $item->total_quantity }} un.</span>
                        </div>
                        <div class="mt-1.5 h-1.5 w-full rounded-full bg-gray-100">
                            <div class="h-1.5 rounded-full bg-primary-600" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="px-4 py-8 text-center text-sm text-gray-400">
                    Nenhuma venda registada nesta data.
                </p>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Indicadores Gerais</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-ui.stat-card status="warning" label="Stock Baixo" :value="$lowStockCount">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>

            <x-ui.stat-card status="warning" label="Próximos da Validade" :value="$nearExpiryCount">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>

            <x-ui.stat-card status="danger" label="Expirados" :value="$expiredCount">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>
        </div>
    </div>
</x-app-layout>

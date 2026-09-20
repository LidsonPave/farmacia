<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Relatórios" subtitle="Fecho diário e indicadores" />
    </x-slot>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('relatorios.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs font-medium text-gray-500">Data</label>
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                Filtrar
            </button>
        </form>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Fecho do Dia — {{ $date->format('d/m/Y') }}</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Vendido</p>
                <p class="mt-1 text-2xl font-bold text-primary-700">{{ number_format($totalSold, 2, ',', '.') }} MT</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Número de Vendas</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $totalSalesCount }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Vendas por Método de Pagamento</h2>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500">Dinheiro</p>
                <p class="mt-1 font-semibold text-gray-900">{{ number_format($paymentTotals['dinheiro'], 2, ',', '.') }} MT</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500">M-Pesa</p>
                <p class="mt-1 font-semibold text-gray-900">{{ number_format($paymentTotals['mpesa'], 2, ',', '.') }} MT</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500">e-Mola</p>
                <p class="mt-1 font-semibold text-gray-900">{{ number_format($paymentTotals['emola'], 2, ',', '.') }} MT</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500">Cartão</p>
                <p class="mt-1 font-semibold text-gray-900">{{ number_format($paymentTotals['cartao'], 2, ',', '.') }} MT</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500">Outro</p>
                <p class="mt-1 font-semibold text-gray-900">{{ number_format($paymentTotals['outro'], 2, ',', '.') }} MT</p>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Top 5 Medicamentos Vendidos</h2>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Medicamento</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Quantidade Vendida</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topMedicines as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-900">{{ $item->medicine->name }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900">{{ $item->total_quantity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-8 text-center text-gray-400">
                                Nenhuma venda registada nesta data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">Indicadores Gerais</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Stock Baixo</p>
                <p class="mt-1 text-2xl font-bold text-amber-600">{{ $lowStockCount }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Próximos da Validade</p>
                <p class="mt-1 text-2xl font-bold text-amber-600">{{ $nearExpiryCount }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Expirados</p>
                <p class="mt-1 text-2xl font-bold text-red-600">{{ $expiredCount }}</p>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-ui.page-heading title="Vendas" subtitle="Histórico de vendas realizadas" />
            <a href="{{ route('vendas.create') }}" class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-800">
                + Nova Venda
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('vendas.index') }}" class="flex flex-wrap items-end gap-4 border-b border-gray-200 bg-gray-50/60 p-4 sm:p-5">
            <div class="w-full sm:w-48">
                <label class="text-xs font-medium text-gray-500">Método de Pagamento</label>
                <select name="payment_method" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="dinheiro" @selected(request('payment_method') === 'dinheiro')>Dinheiro</option>
                    <option value="mpesa" @selected(request('payment_method') === 'mpesa')>M-Pesa</option>
                    <option value="emola" @selected(request('payment_method') === 'emola')>e-Mola</option>
                    <option value="cartao" @selected(request('payment_method') === 'cartao')>Cartão</option>
                    <option value="outro" @selected(request('payment_method') === 'outro')>Outro</option>
                </select>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['payment_method']))
                    <a href="{{ route('vendas.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Data</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Vendedor</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Pagamento</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-4 py-3.5 text-gray-500">{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $sale->user->name }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right text-base font-semibold text-gray-900">{{ number_format($sale->total, 2, ',', '.') }} MT</td>
                            <td class="px-4 py-3.5">
                                <x-ui.payment-badge :method="$sale->payment_method" />
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('vendas.show', $sale) }}" class="rounded px-1.5 py-1 text-primary-700 transition hover:bg-primary-50 hover:text-primary-900">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Nenhuma venda registada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 p-4">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>

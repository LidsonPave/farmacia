<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-ui.page-heading title="Vendas" subtitle="Histórico de vendas realizadas" />
            <a href="{{ route('vendas.create') }}" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                + Nova Venda
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('vendas.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
            <div>
                <label class="text-xs font-medium text-gray-500">Método de Pagamento</label>
                <select name="payment_method" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="dinheiro" @selected(request('payment_method') === 'dinheiro')>Dinheiro</option>
                    <option value="mpesa" @selected(request('payment_method') === 'mpesa')>M-Pesa</option>
                    <option value="emola" @selected(request('payment_method') === 'emola')>e-Mola</option>
                    <option value="cartao" @selected(request('payment_method') === 'cartao')>Cartão</option>
                    <option value="outro" @selected(request('payment_method') === 'outro')>Outro</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['payment_method']))
                    <a href="{{ route('vendas.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Data</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Vendedor</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Total</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Pagamento</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $sale->user->name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ number_format($sale->total, 2, ',', '.') }} MT</td>
                            <td class="px-4 py-3 text-gray-600 capitalize">{{ $sale->payment_method }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('vendas.show', $sale) }}" class="text-primary-700 hover:text-primary-900">Ver</a>
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

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-ui.page-heading title="Venda #{{ $sale->id }}" subtitle="{{ $sale->sold_at->format('d/m/Y H:i') }}" />
            <a href="{{ route('vendas.index') }}" class="text-sm text-primary-700 hover:text-primary-900">
                ← Voltar ao histórico
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid grid-cols-2 gap-4 border-b border-gray-200 pb-4 text-sm sm:grid-cols-4">
            <div>
                <p class="text-gray-500">Vendedor</p>
                <p class="font-medium text-gray-900">{{ $sale->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Data/Hora</p>
                <p class="font-medium text-gray-900">{{ $sale->sold_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Pagamento</p>
                <p class="font-medium capitalize text-gray-900">{{ $sale->payment_method }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total</p>
                <p class="font-semibold text-primary-700">{{ number_format($sale->total, 2, ',', '.') }} MT</p>
            </div>
        </div>

        <table class="mt-4 min-w-full divide-y divide-gray-200 text-sm">
            <thead>
                <tr>
                    <th class="py-2 text-left font-medium text-gray-500">Medicamento</th>
                    <th class="py-2 text-left font-medium text-gray-500">Qtd</th>
                    <th class="py-2 text-left font-medium text-gray-500">Preço Unit.</th>
                    <th class="py-2 text-right font-medium text-gray-500">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($sale->items as $item)
                    <tr>
                        <td class="py-2 text-gray-900">{{ $item->medicine->name }}</td>
                        <td class="py-2 text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-2 text-gray-600">{{ number_format($item->unit_price, 2, ',', '.') }} MT</td>
                        <td class="py-2 text-right text-gray-900">{{ number_format($item->subtotal, 2, ',', '.') }} MT</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 space-y-1 border-t border-gray-200 pt-4 text-right text-sm">
            <p class="text-gray-600">Subtotal: {{ number_format($sale->subtotal, 2, ',', '.') }} MT</p>
            @if($sale->discount_amount > 0)
                <p class="text-gray-600">Desconto: -{{ number_format($sale->discount_amount, 2, ',', '.') }} MT</p>
            @endif
            <p class="text-base font-semibold text-gray-900">Total: {{ number_format($sale->total, 2, ',', '.') }} MT</p>
        </div>
    </div>
</x-app-layout>

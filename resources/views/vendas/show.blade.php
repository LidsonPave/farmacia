<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-ui.page-heading title="Venda #{{ $sale->id }}" subtitle="{{ $sale->sold_at->format('d/m/Y H:i') }}" />
            <div class="flex gap-2">
                <button @click="$dispatch('open-receipt-modal')" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                    Ver Recibo
                </button>
                <a href="{{ route('vendas.index') }}" class="rounded-lg px-4 py-2 text-sm text-primary-700 hover:text-primary-900">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 no-print">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm no-print">
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
            @if($sale->payment_method === 'dinheiro' && $sale->amount_received)
                <p class="text-gray-600">Recebido: {{ number_format($sale->amount_received, 2, ',', '.') }} MT</p>
                <p class="text-gray-600">Troco: {{ number_format($sale->change_amount, 2, ',', '.') }} MT</p>
            @endif
        </div>
    </div>

    <div x-data="{ receiptOpen: false }" @open-receipt-modal.window="receiptOpen = true">
        <div x-show="receiptOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 no-print">
            <div x-show="receiptOpen" @click="receiptOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="receiptOpen" class="relative max-h-[90vh] w-full max-w-sm overflow-y-auto rounded-xl bg-white p-4 shadow-xl">
                <div class="receipt-preview-wrapper">
                    @include('vendas.partials.receipt', ['sale' => $sale])
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button @click="receiptOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                        Fechar
                    </button>
                    <button onclick="window.print()" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="print">
        <div class="receipt-print">
            @include('vendas.partials.receipt', ['sale' => $sale])
        </div>
    </x-slot>
</x-app-layout>

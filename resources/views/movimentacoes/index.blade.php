<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-ui.page-heading title="Movimentações" subtitle="Registo de entradas e saídas de stock" />
            <button
                @click="$dispatch('open-movement-modal')"
                type="button"
                class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800"
            >
                + Nova Movimentação
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div x-data="{ movementOpen: false }" @open-movement-modal.window="movementOpen = true">
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <form method="GET" action="{{ route('movimentacoes.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
                <div>
                    <label class="text-xs font-medium text-gray-500">Medicamento</label>
                    <select name="medicine_id" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todos</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" @selected(request('medicine_id') == $medicine->id)>
                                {{ $medicine->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Tipo</label>
                    <select name="type" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todos</option>
                        <option value="entrada" @selected(request('type') === 'entrada')>Entrada</option>
                        <option value="saida" @selected(request('type') === 'saida')>Saída</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Motivo</label>
                    <select name="reason" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todos</option>
                        <option value="compra" @selected(request('reason') === 'compra')>Compra</option>
                        <option value="venda" @selected(request('reason') === 'venda')>Venda</option>
                        <option value="ajuste" @selected(request('reason') === 'ajuste')>Ajuste</option>
                        <option value="devolucao" @selected(request('reason') === 'devolucao')>Devolução</option>
                        <option value="perda" @selected(request('reason') === 'perda')>Perda</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['medicine_id', 'type', 'reason']))
                        <a href="{{ route('movimentacoes.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
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
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Medicamento</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Tipo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Quantidade</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Lote</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Fornecedor</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Motivo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Referência</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Utilizador</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($movements as $movement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-600">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $movement->medicine->name }}</td>
                                <td class="px-4 py-3">
                                    @if($movement->type === 'entrada')
                                        <x-ui.badge status="success">Entrada</x-ui.badge>
                                    @else
                                        <x-ui.badge status="danger">Saída</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $movement->quantity }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $movement->batch_number ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $movement->supplier?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 capitalize">{{ $movement->reason }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $movement->reference ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $movement->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-400">
                                    Nenhuma movimentação registada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $movements->links() }}
            </div>
        </div>

        {{-- Modal: Nova Movimentação --}}
        <div
            x-show="movementOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div x-show="movementOpen" @click="movementOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="movementOpen" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Nova Movimentação de Stock</h3>

                <form method="POST" action="{{ route('movimentacoes.store') }}" class="mt-4 space-y-4" novalidate>
                    @csrf

                    <div x-data="{ selectedStock: null }">
                        <label class="text-xs font-medium text-gray-500">Medicamento</label>
                        <select name="medicine_id" required @change="selectedStock = $event.target.selectedOptions[0].dataset.stock ?? null" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Selecione...</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" data-stock="{{ $medicine->stock_quantity }}">{{ $medicine->name }} ({{ $medicine->code }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500" x-show="selectedStock !== null">Stock atual: <span x-text="selectedStock"></span></p>
                    </div>

                    <div x-data="{ movementType: 'entrada' }">
                        <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Tipo</label>
                            <select name="type" required x-model="movementType" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Quantidade</label>
                            <input type="number" min="1" name="quantity" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Motivo</label>
                        <select name="reason" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="compra" x-show="movementType === 'entrada'">Compra</option>
                            <option value="devolucao">Devolução</option>
                            <option value="perda" x-show="movementType === 'saida'">Perda</option>
                            <option value="ajuste" x-show="movementType === 'saida'">Ajuste</option>
                        </select>
                    </div>

                        <div x-show="movementType === 'entrada'" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500">Lote</label>
                                <input type="text" name="batch_number" :required="movementType === 'entrada'" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500">Validade do Lote</label>
                                <input type="date" name="batch_expiry_date" :required="movementType === 'entrada'" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div x-show="movementType === 'entrada'">
                            <label class="text-xs font-medium text-gray-500">Fornecedor</label>
                            <select name="supplier_id" :required="movementType === 'entrada'" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Selecione...</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div>
                        <label class="text-xs font-medium text-gray-500">Referência (opcional)</label>
                        <input type="text" name="reference" placeholder="Ex: Fatura 123" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="movementOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

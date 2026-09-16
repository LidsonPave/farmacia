<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Stock" subtitle="Consulta do stock atual de medicamentos" />
    </x-slot>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
            <div class="min-w-[200px] flex-1">
                <label class="text-xs font-medium text-gray-500">Pesquisar</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nome ou código..."
                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                >
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">Categoria</label>
                <select name="category_id" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">Stock</label>
                <select name="stock_status" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="baixo" @selected(request('stock_status') === 'baixo')>Stock Baixo</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'stock_status']))
                    <a href="{{ route('stock.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Código</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Medicamento</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Categoria</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Stock Atual</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Stock Mínimo</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $medicine->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $medicine->category->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $medicine->stock_quantity }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $medicine->minimum_stock }}</td>
                            <td class="px-4 py-3">
                                @if($medicine->isLowStock())
                                    <x-ui.badge status="warning">Stock Baixo</x-ui.badge>
                                @else
                                    <x-ui.badge status="success">Normal</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                Nenhum medicamento encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 p-4">
            {{ $medicines->links() }}
        </div>
    </div>
</x-app-layout>

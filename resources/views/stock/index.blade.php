<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Stock" subtitle="Consulta do stock atual de medicamentos" />
    </x-slot>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap items-end gap-4 border-b border-gray-200 bg-gray-50/60 p-4 sm:p-5">
            <div class="w-full min-w-[200px] sm:w-auto sm:flex-1">
                <label class="text-xs font-medium text-gray-500">Pesquisar</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nome ou código..."
                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                >
            </div>

            <div class="w-full sm:w-44">
                <label class="text-xs font-medium text-gray-500">Categoria</label>
                <select name="category_id" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-40">
                <label class="text-xs font-medium text-gray-500">Stock</label>
                <select name="stock_status" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="baixo" @selected(request('stock_status') === 'baixo')>Stock Baixo</option>
                </select>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'stock_status']))
                    <a href="{{ route('stock.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Medicamento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Categoria</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Stock Atual</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Stock Mínimo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $medicine)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-4 py-3.5 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                            <td class="px-4 py-3.5 font-medium text-gray-900">{{ $medicine->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $medicine->category->name }}</td>
                            <td class="px-4 py-3.5 text-right text-base font-semibold text-gray-900">{{ $medicine->stock_quantity }}</td>
                            <td class="px-4 py-3.5 text-right text-gray-500">{{ $medicine->minimum_stock }}</td>
                            <td class="px-4 py-3.5">
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

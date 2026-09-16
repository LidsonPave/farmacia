<x-app-layout>
    <x-slot name="header">
        <div
            x-data="{}"
            class="flex items-center justify-between"
        >
            <x-ui.page-heading title="Medicamentos" subtitle="Gerir o catálogo de medicamentos" />
            <button
                @click="$dispatch('open-create-modal')"
                type="button"
                class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800"
            >
                + Novo Medicamento
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div
        x-data="{
            createOpen: false,
            editOpen: false,
            deleteOpen: false,
            editing: {},
            deleting: {}
        }"
        @open-create-modal.window="createOpen = true"
        @open-edit-modal.window="editing = $event.detail; editOpen = true"
        @open-delete-modal.window="deleting = $event.detail; deleteOpen = true"
    >
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <form method="GET" action="{{ route('medicines.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
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

                <div>
                    <label class="text-xs font-medium text-gray-500">Validade</label>
                    <select name="expiry_status" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todas</option>
                        <option value="proximo" @selected(request('expiry_status') === 'proximo')>Próximo da Validade</option>
                        <option value="expirado" @selected(request('expiry_status') === 'expirado')>Expirado</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'category_id', 'stock_status', 'expiry_status']))
                        <a href="{{ route('medicines.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
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
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Preço Venda</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Stock</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Validade</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($medicines as $medicine)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $medicine->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $medicine->category->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ number_format($medicine->sale_price, 2, ',', '.') }} MT</td>
                                <td class="px-4 py-3 text-gray-600">{{ $medicine->stock_quantity }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $medicine->expiry_date?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($medicine->status === 'inativo')
                                        <x-ui.badge status="neutral">Inativo</x-ui.badge>
                                    @elseif($medicine->isExpired())
                                        <x-ui.badge status="danger">Expirado</x-ui.badge>
                                    @elseif($medicine->isNearExpiry())
                                        <x-ui.badge status="warning">Próx. Validade</x-ui.badge>
                                    @elseif($medicine->isLowStock())
                                        <x-ui.badge status="warning">Stock Baixo</x-ui.badge>
                                    @else
                                        <x-ui.badge status="success">Normal</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        class="text-primary-700 hover:text-primary-900"
                                        @click="$dispatch('open-edit-modal', {
                                            id: {{ $medicine->id }},
                                            category_id: {{ $medicine->category_id }},
                                            name: @js($medicine->name),
                                            code: @js($medicine->code),
                                            purchase_price: {{ $medicine->purchase_price }},
                                            sale_price: {{ $medicine->sale_price }},
                                            stock_quantity: {{ $medicine->stock_quantity }},
                                            minimum_stock: {{ $medicine->minimum_stock }},
                                            expiry_date: @js($medicine->expiry_date?->format('Y-m-d'))
                                        })"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-3 text-red-600 hover:text-red-800"
                                        @click="$dispatch('open-delete-modal', { id: {{ $medicine->id }}, name: @js($medicine->name) })"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-400">
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

        {{-- Modal: Novo Medicamento --}}
        <div
            x-show="createOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div x-show="createOpen".opacity @click="createOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="createOpen" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Novo Medicamento</h3>

                <form method="POST" action="{{ route('medicines.store') }}" class="mt-4 space-y-4" novalidate>
                    @csrf

                    <div>
                        <label class="text-xs font-medium text-gray-500">Categoria</label>
                        <select name="category_id" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Código</label>
                        <input type="text" name="code" value="{{ $nextCode }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Preço de Compra (MT)</label>
                            <input type="number" step="0.01" min="0" name="purchase_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Preço de Venda (MT)</label>
                            <input type="number" step="0.01" min="0" name="sale_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Stock Inicial</label>
                            <input type="number" min="0" name="stock_quantity" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Stock Mínimo</label>
                            <input type="number" min="0" name="minimum_stock" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Validade (opcional)</label>
                        <input type="date" name="expiry_date" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="createOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Editar Medicamento --}}
        <div
            x-show="editOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div x-show="editOpen".opacity @click="editOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="editOpen" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Editar Medicamento</h3>

                <form method="POST" :action="'/medicines/' + editing.id" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-xs font-medium text-gray-500">Categoria</label>
                        <select name="category_id" x-model="editing.category_id" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" x-model="editing.name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Código</label>
                        <input type="text" name="code" x-model="editing.code" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Preço de Compra (MT)</label>
                            <input type="number" step="0.01" min="0" name="purchase_price" x-model="editing.purchase_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Preço de Venda (MT)</label>
                            <input type="number" step="0.01" min="0" name="sale_price" x-model="editing.sale_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Stock Atual</label>
                            <input type="number" min="0" name="stock_quantity" x-model="editing.stock_quantity" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Stock Mínimo</label>
                            <input type="number" min="0" name="minimum_stock" x-model="editing.minimum_stock" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">Validade (opcional)</label>
                        <input type="date" name="expiry_date" x-model="editing.expiry_date" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="editOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">
                            Guardar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Confirmar Eliminação --}}
        <div
            x-show="deleteOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div x-show="deleteOpen".opacity @click="deleteOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="deleteOpen" class="relative max-h-[90vh] w-full max-w-sm overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Confirmar Eliminação</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Tem a certeza que deseja eliminar <span class="font-medium" x-text="deleting.name"></span>?
                    Se este medicamento já tiver movimentações ou vendas associadas, será desativado em vez de eliminado, para preservar o histórico.
                </p>

                <form method="POST" :action="'/medicines/' + deleting.id" class="mt-4 flex justify-end gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

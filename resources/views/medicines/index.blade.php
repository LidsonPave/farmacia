<x-app-layout>
    <x-slot name="header">
        <div
            x-data="{}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <x-ui.page-heading title="Medicamentos" subtitle="Gerir o catálogo de medicamentos" />
            <button
                @click="$dispatch('open-create-modal')"
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-800"
            >
                + Novo Medicamento
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    @endif

    <div
        x-data="{
            createOpen: {{ $errors->any() && old('_form') === 'create' ? 'true' : 'false' }},
            editOpen: {{ $errors->any() && old('_form') === 'edit' ? 'true' : 'false' }},
            deleteOpen: false,
            editing: {},
            deleting: {}
        }"
        @open-create-modal.window="createOpen = true"
        @open-edit-modal.window="editing = $event.detail; editOpen = true"
        @open-delete-modal.window="deleting = $event.detail; deleteOpen = true"
    >
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <form method="GET" action="{{ route('medicines.index') }}" class="flex flex-wrap items-end gap-4 border-b border-gray-200 bg-gray-50/60 p-4 sm:p-5">
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

                <div class="w-full sm:w-48">
                    <label class="text-xs font-medium text-gray-500">Validade</label>
                    <select name="expiry_status" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todas</option>
                        <option value="proximo" @selected(request('expiry_status') === 'proximo')>Próximo da Validade</option>
                        <option value="expirado" @selected(request('expiry_status') === 'expirado')>Expirado</option>
                    </select>
                </div>

                <div class="flex w-full gap-2 sm:w-auto">
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'category_id', 'stock_status', 'expiry_status']))
                        <a href="{{ route('medicines.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700">
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
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Preço Venda</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Validade</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($medicines as $medicine)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-3.5 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                                <td class="px-4 py-3.5 font-medium text-gray-900">{{ $medicine->name }}</td>
                                <td class="px-4 py-3.5 text-gray-600">{{ $medicine->category->name }}</td>
                                <td class="px-4 py-3.5 text-right text-gray-700">{{ number_format($medicine->sale_price, 2, ',', '.') }} MT</td>
                                <td class="px-4 py-3.5 text-right text-gray-700">{{ $medicine->stock_quantity }}</td>
                                <td class="px-4 py-3.5 text-gray-600">
                                    {{ $medicine->expiry_date?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3.5">
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
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                    <button
                                        type="button"
                                        class="rounded px-1.5 py-1 inline-flex items-center gap-1 text-amber-600 transition hover:bg-amber-50 hover:text-amber-800"
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-1 inline-flex items-center gap-1 rounded px-1.5 py-1 text-red-600 transition hover:bg-red-50 hover:text-red-800"
                                        @click="$dispatch('open-delete-modal', { id: {{ $medicine->id }}, name: @js($medicine->name) })"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
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
            <div x-show="createOpen" @click="createOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="createOpen" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Novo Medicamento</h3>

                <form method="POST" action="{{ route('medicines.store') }}" class="mt-4 space-y-6" novalidate>
                    @csrf
                    <input type="hidden" name="_form" value="create">

                    <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Informação do Medicamento</p>

                        <div>
                            <label class="text-xs font-medium text-gray-500">Nome</label>
                            <input type="text" name="name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500">Código</label>
                            <input type="text" name="code" value="{{ $nextCode }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500">Categoria</label>
                            <select name="category_id" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Preços</p>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500">Preço de Compra (MT)</label>
                                <input type="number" step="0.01" min="0" name="purchase_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('purchase_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500">Preço de Venda (MT)</label>
                                <input type="number" step="0.01" min="0" name="sale_price" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('sale_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Stock</p>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500">Stock Inicial</label>
                                <input type="number" min="0" name="stock_quantity" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('stock_quantity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500">Stock Mínimo</label>
                                <input type="number" min="0" name="minimum_stock" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('minimum_stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Validade</p>

                        <div>
                            <label class="text-xs font-medium text-gray-500">Validade (opcional)</label>
                            <input type="date" name="expiry_date" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('expiry_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
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
            <div x-show="editOpen" @click="editOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

            <div x-show="editOpen" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Editar Medicamento</h3>

                <form method="POST" :action="'/medicines/' + editing.id" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="edit">

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
            <div x-show="deleteOpen" @click="deleteOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

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

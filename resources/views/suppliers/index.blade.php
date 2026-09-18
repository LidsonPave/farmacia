<x-app-layout>
    <x-slot name="header">
        <div
            x-data="{}"
            class="flex items-center justify-between"
        >
            <x-ui.page-heading title="Fornecedores" subtitle="Gerir fornecedores de medicamentos" />
            <button
                @click="$dispatch('open-create-modal')"
                type="button"
                class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800"
            >
                + Novo Fornecedor
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
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
            <form method="GET" action="{{ route('fornecedores.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
                <div class="min-w-[200px] flex-1">
                    <label class="text-xs font-medium text-gray-500">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nome ou telefone..."
                        class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('fornecedores.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nome</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Telefone</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $supplier->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $supplier->phone ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        class="text-primary-700 hover:text-primary-900"
                                        @click="$dispatch('open-edit-modal', {
                                            id: {{ $supplier->id }},
                                            name: @js($supplier->name),
                                            phone: @js($supplier->phone)
                                        })"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-3 text-red-600 hover:text-red-800"
                                        @click="$dispatch('open-delete-modal', { id: {{ $supplier->id }}, name: @js($supplier->name) })"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                    Nenhum fornecedor encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $suppliers->links() }}
            </div>
        </div>

        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="createOpen" @click="createOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
            <div x-show="createOpen" class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Novo Fornecedor</h3>
                <form method="POST" action="{{ route('fornecedores.store') }}" class="mt-4 space-y-4" novalidate>
                    @csrf
                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Telefone (opcional)</label>
                        <input type="text" name="phone" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="createOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="editOpen" @click="editOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
            <div x-show="editOpen" class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Editar Fornecedor</h3>
                <form method="POST" :action="'/fornecedores/' + editing.id" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" x-model="editing.name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Telefone (opcional)</label>
                        <input type="text" name="phone" x-model="editing.phone" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="editOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800">Guardar Alterações</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="deleteOpen" @click="deleteOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
            <div x-show="deleteOpen" class="relative max-h-[90vh] w-full max-w-sm overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Confirmar Eliminação</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Tem a certeza que deseja eliminar <span class="font-medium" x-text="deleting.name"></span>?
                    Se este fornecedor já tiver movimentações associadas, a eliminação será bloqueada.
                </p>
                <form method="POST" :action="'/fornecedores/' + deleting.id" class="mt-4 flex justify-end gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

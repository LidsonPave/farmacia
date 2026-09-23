<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-ui.page-heading title="Utilizadores" subtitle="Gerir contas de acesso ao sistema" />
            <button
                @click="$dispatch('open-create-modal')"
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-800"
            >
                + Novo Utilizador
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    @endif

    @if(session('error'))
        <x-ui.alert type="error">{{ session('error') }}</x-ui.alert>
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
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-end gap-4 border-b border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                <div class="w-full min-w-[200px] sm:w-auto sm:flex-1">
                    <label class="text-xs font-medium text-gray-500">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nome ou email..."
                        class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                </div>

                <div class="w-full sm:w-44">
                    <label class="text-xs font-medium text-gray-500">Perfil</label>
                    <select name="role" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Todos</option>
                        <option value="admin" @selected(request('role') === 'admin')>Administrador</option>
                        <option value="funcionario" @selected(request('role') === 'funcionario')>Funcionário</option>
                    </select>
                </div>

                <div class="flex w-full gap-2 sm:w-auto">
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ route('users.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Perfil</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-3.5 font-medium text-gray-900">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="ml-1 text-xs text-gray-400">(Você)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3.5">
                                    @if($user->role === 'admin')
                                        <x-ui.badge status="neutral">Administrador</x-ui.badge>
                                    @else
                                        <x-ui.badge status="success">Funcionário</x-ui.badge>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1 rounded px-1.5 py-1 text-amber-600 transition hover:bg-amber-50 hover:text-amber-800"
                                        @click="$dispatch('open-edit-modal', {
                                            id: {{ $user->id }},
                                            name: @js($user->name),
                                            email: @js($user->email),
                                            role: @js($user->role)
                                        })"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                        Editar
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button
                                            type="button"
                                            class="ml-1 inline-flex items-center gap-1 rounded px-1.5 py-1 text-red-600 transition hover:bg-red-50 hover:text-red-800"
                                            @click="$dispatch('open-delete-modal', { id: {{ $user->id }}, name: @js($user->name) })"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                    Nenhum utilizador encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $users->links() }}
            </div>
        </div>

        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="createOpen" @click="createOpen = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
            <div x-show="createOpen" class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Novo Utilizador</h3>
                <form method="POST" action="{{ route('users.store') }}" class="mt-4 space-y-4" novalidate>
                    @csrf
                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Email</label>
                        <input type="email" name="email" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Perfil</label>
                        <select name="role" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="funcionario">Funcionário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Password</label>
                            <input type="password" name="password" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Confirmar Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
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
                <h3 class="text-lg font-semibold text-gray-900">Editar Utilizador</h3>
                <form method="POST" :action="'/users/' + editing.id" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-xs font-medium text-gray-500">Nome</label>
                        <input type="text" name="name" x-model="editing.name" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Email</label>
                        <input type="email" name="email" x-model="editing.email" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Perfil</label>
                        <select name="role" x-model="editing.role" required class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="funcionario">Funcionário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Nova Password (opcional)</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Confirmar Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Deixe a password em branco para não a alterar.</p>
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
                    Se este utilizador já tiver vendas ou movimentações associadas, a eliminação será bloqueada.
                </p>
                <form method="POST" :action="'/users/' + deleting.id" class="mt-4 flex justify-end gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

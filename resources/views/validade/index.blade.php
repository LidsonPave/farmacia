<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Validade" subtitle="Controlo de validade dos medicamentos" />
    </x-slot>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('validade.index') }}" class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
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
                <label class="text-xs font-medium text-gray-500">Estado</label>
                <select name="status" class="mt-1 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                    <option value="proximo" @selected(request('status') === 'proximo')>Próximo da Validade</option>
                    <option value="expirado" @selected(request('status') === 'expirado')>Expirado</option>
                    <option value="sem_validade" @selected(request('status') === 'sem_validade')>Sem Validade</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('validade.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
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
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Validade</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Dias Restantes</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $medicine->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $medicine->category->name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $medicine->expiry_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($medicine->expiry_date)
                                    {{ (int) now()->diffInDays($medicine->expiry_date, false) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(! $medicine->expiry_date)
                                    <x-ui.badge status="neutral">Sem Validade</x-ui.badge>
                                @elseif($medicine->isExpired())
                                    <x-ui.badge status="danger">Expirado</x-ui.badge>
                                @elseif($medicine->isNearExpiry())
                                    <x-ui.badge status="warning">Próx. Validade</x-ui.badge>
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

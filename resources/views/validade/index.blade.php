<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Validade" subtitle="Controlo de validade dos medicamentos" />
    </x-slot>

    @if(session('success'))
        <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.stat-card
            status="danger"
            label="Expirados"
            :value="$expiredCount"
            context="Ação crítica"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-slot>
        </x-ui.stat-card>

        <x-ui.stat-card
            status="warning"
            label="Próximos da Validade"
            :value="$nearExpiryCount"
            :context="'Dentro de ' . $warningDays . ' dias'"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </x-slot>
        </x-ui.stat-card>

        <x-ui.stat-card
            status="success"
            label="Normal"
            :value="$normalCount"
            context="Situação controlada"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-slot>
        </x-ui.stat-card>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('validade.index') }}" class="flex flex-wrap items-end gap-4 border-b border-gray-200 bg-gray-50/60 p-4 sm:p-5">
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

            <div class="w-full sm:w-48">
                <label class="text-xs font-medium text-gray-500">Estado</label>
                <select name="status" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos</option>
                    <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                    <option value="proximo" @selected(request('status') === 'proximo')>Próximo da Validade</option>
                    <option value="expirado" @selected(request('status') === 'expirado')>Expirado</option>
                    <option value="sem_validade" @selected(request('status') === 'sem_validade')>Sem Validade</option>
                </select>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('validade.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Validade</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Dias Restantes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $medicine)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-4 py-3.5 font-mono text-xs text-gray-600">{{ $medicine->code }}</td>
                            <td class="px-4 py-3.5 font-medium text-gray-900">{{ $medicine->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $medicine->category->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">
                                {{ $medicine->expiry_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                @if($medicine->expiry_date)
                                    <span @class([
                                        'text-base font-semibold',
                                        'text-red-600' => $medicine->isExpired(),
                                        'text-amber-600' => $medicine->isNearExpiry(),
                                        'text-gray-700' => ! $medicine->isExpired() && ! $medicine->isNearExpiry(),
                                    ])>
                                        {{ (int) now()->diffInDays($medicine->expiry_date, false) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
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

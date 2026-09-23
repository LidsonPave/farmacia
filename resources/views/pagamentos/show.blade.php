<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pedido de Pagamento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-950 p-4">
    <div
        x-data="{
            status: '{{ $paymentRequest->status }}',
            pin: '',
            loading: false,
            error: '',

            async confirmPayment() {
                if (this.pin.length !== 4) {
                    this.error = 'Introduza um PIN de 4 dígitos.';
                    return;
                }

                this.loading = true;
                this.error = '';

                try {
                    const response = await fetch('{{ route('pagamentos.confirm', $paymentRequest->reference) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ pin: this.pin }),
                    });

                    const data = await response.json();
                    this.status = data.status;

                    if (this.status === 'approved') {
                        setTimeout(() => window.close(), 2000);
                    }
                } catch (e) {
                    this.error = 'Erro ao confirmar. Tente novamente.';
                } finally {
                    this.loading = false;
                }
            }
        }"
        class="w-full max-w-xs overflow-hidden rounded-2xl bg-white shadow-2xl"
    >
        <div class="flex items-center gap-3 {{ $paymentRequest->payment_method === 'mpesa' ? 'bg-primary-700' : 'bg-teal-700' }} px-5 py-4">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-bold text-white">
                {{ $paymentRequest->payment_method === 'mpesa' ? 'M' : 'e' }}
            </span>
            <div class="leading-tight text-white">
                <p class="text-sm font-semibold">{{ $paymentRequest->payment_method === 'mpesa' ? 'M-Pesa' : 'e-Mola' }}</p>
                <p class="text-xs text-white/70">Pedido de Pagamento</p>
            </div>
        </div>

        <template x-if="status === 'pending'">
            <div class="p-5">
                <div class="rounded-lg bg-gray-50 p-4 text-center">
                    <p class="text-xs text-gray-500">Valor a pagar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($paymentRequest->amount, 2, ',', '.') }} MT</p>
                </div>

                <p class="mt-3 text-center text-xs text-gray-500">
                    Cliente: {{ $paymentRequest->phone }}
                </p>

                <div class="mt-4">
                    <label class="text-xs font-medium text-gray-500">Introduza o seu PIN para confirmar</label>
                    <input
                        type="password"
                        inputmode="numeric"
                        maxlength="4"
                        x-model="pin"
                        placeholder="••••"
                        class="mt-1.5 w-full rounded-lg border-gray-300 text-center text-2xl tracking-[0.5em] focus:border-primary-500 focus:ring-primary-500"
                    >
                    <p x-show="error" x-text="error" class="mt-1 text-xs text-red-600"></p>
                </div>

                <button
                    @click="confirmPayment"
                    :disabled="loading"
                    class="mt-4 w-full rounded-lg bg-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-800 disabled:opacity-50"
                >
                    <span x-show="!loading">Confirmar Pagamento</span>
                    <span x-show="loading">A processar...</span>
                </button>

                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-center">
                    <p class="text-xs font-semibold text-amber-800">MODO DEMONSTRAÇÃO</p>
                    <p class="text-xs text-amber-700">Nenhum pagamento real será efetuado.</p>
                </div>
            </div>
        </template>

        <template x-if="status === 'approved'">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <p class="mt-3 text-base font-semibold text-gray-900">Pagamento Aprovado</p>
                <p class="mt-1 text-xl font-bold text-primary-700">{{ number_format($paymentRequest->amount, 2, ',', '.') }} MT</p>

                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-center">
                    <p class="text-xs font-semibold text-amber-800">MODO DEMONSTRAÇÃO</p>
                    <p class="text-xs text-amber-700">Nenhum pagamento real foi efetuado.</p>
                </div>

                <p class="mt-3 text-xs text-gray-400">Esta janela fecha automaticamente...</p>
            </div>
        </template>
    </div>
</body>
</html>

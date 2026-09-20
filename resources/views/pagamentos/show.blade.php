<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pedido de Pagamento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-black p-4">
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
        class="w-full max-w-xs rounded-lg bg-gray-900 p-5 font-mono text-white shadow-2xl"
    >
        <template x-if="status === 'pending'">
            <div>
                <p class="text-xs text-gray-400">
                    {{ $paymentRequest->payment_method === 'mpesa' ? '*150#' : '*898#' }}
                </p>
                <div class="mt-3 border-t border-gray-700 pt-3 text-sm leading-relaxed">
                    <p>{{ $paymentRequest->payment_method === 'mpesa' ? 'M-PESA' : 'E-MOLA' }} - Pedido de Pagamento</p>
                    <p class="mt-2">Valor: {{ number_format($paymentRequest->amount, 2, ',', '.') }} MT</p>
                    <p class="mt-2">Introduza o seu PIN para confirmar:</p>

                    <input
                        type="password"
                        inputmode="numeric"
                        maxlength="4"
                        x-model="pin"
                        placeholder="____"
                        class="mt-2 w-full rounded border-none bg-gray-800 px-3 py-2 text-center text-lg tracking-[0.5em] text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-green-500"
                    >
                    <p x-show="error" x-text="error" class="mt-1 text-xs text-red-400"></p>

                    <button
                        @click="confirmPayment"
                        :disabled="loading"
                        class="mt-3 w-full rounded bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50"
                    >
                        <span x-show="!loading">1. Confirmar</span>
                        <span x-show="loading">A processar...</span>
                    </button>
                </div>

                <p class="mt-4 text-center text-[10px] text-gray-500">
                    Modo demonstração — nenhum pagamento real será efetuado.
                </p>
            </div>
        </template>

        <template x-if="status === 'approved'">
            <div class="text-center">
                <p class="text-2xl">✓</p>
                <p class="mt-2 text-sm font-semibold text-green-400">Pagamento Aprovado</p>
                <p class="text-xs text-gray-400">
                    {{ $paymentRequest->payment_method === 'mpesa' ? 'M-Pesa' : 'e-Mola' }} — Demonstração
                </p>
                <p class="mt-3 text-[10px] text-gray-500">Esta janela fecha automaticamente...</p>
            </div>
        </template>
    </div>
</body>
</html>

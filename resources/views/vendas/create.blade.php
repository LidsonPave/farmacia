<x-app-layout>
    <x-slot name="header">
        <x-ui.page-heading title="Nova Venda" subtitle="Ponto de venda (PDV)" />
    </x-slot>

    <div
        x-data="posApp()"
        class="grid grid-cols-1 gap-4 lg:grid-cols-3"
    >
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm lg:col-span-2">
            <label class="text-xs font-medium text-gray-500">Pesquisar medicamento</label>
            <input
                type="text"
                x-model="search"
                placeholder="Nome ou código..."
                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
            >

            <div class="mt-4 max-h-96 space-y-2 overflow-y-auto">
                <template x-for="medicine in filteredMedicines()" :key="medicine.id">
                    <button
                        type="button"
                        @click="addToCart(medicine)"
                        class="flex w-full items-center justify-between rounded-lg border border-gray-200 p-3 text-left hover:border-primary-500 hover:bg-primary-50"
                    >
                        <div>
                            <p class="text-sm font-medium text-gray-900" x-text="medicine.name"></p>
                            <p class="text-xs text-gray-500">
                                <span x-text="medicine.code"></span> · Stock: <span x-text="medicine.stock_quantity"></span>
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-primary-700" x-text="formatMoney(medicine.sale_price) + ' MT'"></p>
                    </button>
                </template>

                <p x-show="filteredMedicines().length === 0" class="py-6 text-center text-sm text-gray-400">
                    Nenhum medicamento encontrado.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">Carrinho</h3>

            <div class="mt-3 space-y-3">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="flex items-start justify-between">
                            <p class="text-sm font-medium text-gray-900" x-text="item.name"></p>
                            <button type="button" @click="removeFromCart(index)" class="text-xs text-red-600 hover:text-red-800">
                                Remover
                            </button>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="decrementQuantity(index)" class="h-7 w-7 rounded border border-gray-300 text-sm hover:bg-gray-100">−</button>
                                <input type="number" min="1" :max="item.stock_quantity" x-model.number="item.quantity" @change="clampQuantity(index)" class="w-14 rounded border-gray-300 text-center text-sm">
                                <button type="button" @click="incrementQuantity(index)" class="h-7 w-7 rounded border border-gray-300 text-sm hover:bg-gray-100">+</button>
                            </div>
                            <p class="text-sm font-semibold text-gray-900" x-text="formatMoney(item.sale_price * item.quantity) + ' MT'"></p>
                        </div>
                    </div>
                </template>

                <p x-show="cart.length === 0" class="py-6 text-center text-sm text-gray-400">
                    Carrinho vazio. Pesquise um medicamento para começar.
                </p>
            </div>

            <div class="mt-4 space-y-3 border-t border-gray-200 pt-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-medium text-gray-500">Desconto</label>
                        <select x-model="discountType" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="none">Sem desconto</option>
                            <option value="fixed">Valor fixo (MT)</option>
                            <option value="percentage">Percentagem (%)</option>
                        </select>
                    </div>
                    <div x-show="discountType !== 'none'">
                        <label class="text-xs font-medium text-gray-500">Valor</label>
                        <input type="number" min="0" step="0.01" x-model.number="discountValue" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span x-text="formatMoney(subtotal()) + ' MT'"></span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600" x-show="discountAmount() > 0">
                        <span>Desconto</span>
                        <span x-text="'- ' + formatMoney(discountAmount()) + ' MT'"></span>
                    </div>
                    <div class="flex justify-between text-base font-semibold text-gray-900">
                        <span>Total</span>
                        <span x-text="formatMoney(total()) + ' MT'"></span>
                    </div>
                </div>
            </div>
            </div>

            <div class="mt-4">
                <label class="text-xs font-medium text-gray-500">Método de Pagamento</label>
                <select x-model="paymentMethod" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="dinheiro">Dinheiro</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="emola">e-Mola</option>
                    <option value="cartao">Cartão</option>
                    <option value="outro">Outro</option>
                </select>

            <div x-show="paymentMethod === 'mpesa' || paymentMethod === 'emola'" class="mt-4">
                <label class="text-xs font-medium text-gray-500">Número do Cliente</label>
                <input type="text" x-model="customerPhone" placeholder="84xxxxxxx" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            </div>

            <div x-show="paymentRequestStatus === 'sent'" class="mt-4 rounded-lg border border-primary-200 bg-primary-50 p-4 text-center">
                <p class="text-sm font-medium text-gray-900">Aguardando confirmação do cliente...</p>
                <div class="mt-3 flex justify-center" id="qrcode-container"></div>
                <p class="mt-2 text-xs text-gray-500">Ou envie este link ao cliente:</p>
                <p class="mt-1 break-all text-xs text-primary-700" x-text="paymentUrl"></p>
            </div>

            <div x-show="paymentRequestStatus === 'approved'" class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-center">
                <p class="text-sm font-semibold text-green-700">Pagamento Aprovado — Demonstração</p>
            </div>

            <button
                type="button"
                x-show="(paymentMethod === 'mpesa' || paymentMethod === 'emola') && paymentRequestStatus === 'idle'"
                @click="sendPaymentRequest"
                class="mt-4 w-full rounded-lg bg-primary-100 px-4 py-3 text-sm font-medium text-primary-700 hover:bg-primary-200 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Enviar Pedido de Pagamento
            </button>
            <form method="POST" action="{{ route('vendas.store') }}" @submit="beforeSubmit">
                @csrf
                <template x-for="(item, index) in cart" :key="'input-' + item.id">
                    <div>
                        <input type="hidden" :name="'items[' + index + '][medicine_id]'" :value="item.id">
                        <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                    </div>
                </template>
                <input type="hidden" name="payment_method" x-model="paymentMethod">
                <input type="hidden" name="discount_type" x-model="discountType">
                <input type="hidden" name="discount_value" x-model="discountValue">

                <button
                    type="submit"
                    x-show="paymentMethod !== 'mpesa' && paymentMethod !== 'emola' || paymentRequestStatus === 'approved'"
                    :disabled="cart.length === 0"
                    class="mt-4 w-full rounded-lg bg-primary-700 px-4 py-3 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Finalizar Venda
                </button>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    function posApp() {
        return {
            search: '',
            cart: [],
            paymentMethod: 'dinheiro',
            discountType: 'none',
            discountValue: 0,
            customerPhone: '',
            paymentRequestStatus: 'idle',
            paymentUrl: '',
            paymentReference: null,
            medicines: @json($medicines),

            filteredMedicines() {
                if (!this.search || this.search.length < 2) {
                    return [];
                }

                const term = this.search.toLowerCase();

                return this.medicines.filter(m =>
                    m.name.toLowerCase().includes(term) || m.code.toLowerCase().includes(term)
                );
            },

            addToCart(medicine) {
                const existing = this.cart.find(item => item.id === medicine.id);

                if (existing) {
                    if (existing.quantity < medicine.stock_quantity) {
                        existing.quantity++;
                    }
                    return;
                }

                this.cart.push({
                    id: medicine.id,
                    name: medicine.name,
                    sale_price: parseFloat(medicine.sale_price),
                    stock_quantity: medicine.stock_quantity,
                    quantity: 1,
                });
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            incrementQuantity(index) {
                const item = this.cart[index];
                if (item.quantity < item.stock_quantity) {
                    item.quantity++;
                }
            },

            decrementQuantity(index) {
                const item = this.cart[index];
                if (item.quantity > 1) {
                    item.quantity--;
                }
            },

            clampQuantity(index) {
                const item = this.cart[index];
                if (item.quantity < 1) item.quantity = 1;
                if (item.quantity > item.stock_quantity) item.quantity = item.stock_quantity;
            },

            subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.sale_price * item.quantity), 0);
            },

            discountAmount() {
                const sub = this.subtotal();
                const value = parseFloat(this.discountValue) || 0;

                if (this.discountType === 'fixed') {
                    return Math.min(value, sub);
                }

                if (this.discountType === 'percentage') {
                    return sub * (Math.min(value, 100) / 100);
                }

                return 0;
            },

            total() {
                return this.subtotal() - this.discountAmount();
            },

            formatMoney(value) {
                return Number(value).toFixed(2).replace('.', ',');
            },


            async sendPaymentRequest() {
                if (!this.customerPhone || this.cart.length === 0) {
                    return;
                }

                this.paymentRequestStatus = "sending";

                try {
                    const response = await fetch("{{ route('pagamentos.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                        },
                        body: JSON.stringify({
                            phone: this.customerPhone,
                            amount: this.total(),
                            payment_method: this.paymentMethod,
                        }),
                    });

                    const data = await response.json();
                    this.paymentReference = data.reference;
                    this.paymentUrl = data.url;
                    this.paymentRequestStatus = "sent";
                    window.open(this.paymentUrl, "_blank");

                    this.pollInterval = setInterval(() => this.checkPaymentStatus(), 2000);
                } catch (e) {
                    console.error("Erro ao criar pedido de pagamento", e);
                    this.paymentRequestStatus = "idle";
                }
            },

            async checkPaymentStatus() {
                if (!this.paymentReference) {
                    return;
                }

                const response = await fetch(`/pagamentos/${this.paymentReference}/status`);
                const data = await response.json();

                if (data.status === "approved") {
                    this.paymentRequestStatus = "approved";
                    clearInterval(this.pollInterval);
                }
            },
            beforeSubmit(event) {
                if (this.cart.length === 0) {
                    event.preventDefault();
                }
            },
        };
    }
</script>
@endpush
</x-app-layout>

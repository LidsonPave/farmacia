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

            <div class="mt-4 space-y-1 border-t border-gray-200 pt-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span x-text="formatMoney(subtotal()) + ' MT'"></span>
                </div>
                <div class="flex justify-between text-base font-semibold text-gray-900">
                    <span>Total</span>
                    <span x-text="formatMoney(subtotal()) + ' MT'"></span>
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
            </div>

            <form method="POST" action="{{ route('vendas.store') }}" @submit="beforeSubmit">
                @csrf
                <template x-for="(item, index) in cart" :key="'input-' + item.id">
                    <div>
                        <input type="hidden" :name="'items[' + index + '][medicine_id]'" :value="item.id">
                        <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                    </div>
                </template>
                <input type="hidden" name="payment_method" x-model="paymentMethod">

                <button
                    type="submit"
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

            formatMoney(value) {
                return Number(value).toFixed(2).replace('.', ',');
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

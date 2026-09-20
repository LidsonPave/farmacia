<div class="receipt">
    <p class="receipt-center receipt-bold">{{ config('pharmacy.name') }}</p>
    <p class="receipt-center">{{ config('pharmacy.address') }}</p>
    <p class="receipt-center">Tel: {{ config('pharmacy.phone') }}</p>
    @if(config('pharmacy.nuit'))
        <p class="receipt-center">NUIT: {{ config('pharmacy.nuit') }}</p>
    @endif

    <p class="receipt-line">--------------------------------</p>

    <p class="receipt-center receipt-bold">RECIBO DE VENDA</p>
    <p>Nº: VD-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p>{{ $sale->sold_at->format('d/m/Y H:i') }}</p>
    <p>Operador: {{ $sale->user->name }}</p>

    <p class="receipt-line">--------------------------------</p>

    <p class="receipt-bold">PRODUTOS</p>
    @foreach($sale->items as $item)
        <p>{{ $item->medicine->name }}</p>
        <div class="receipt-row">
            <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 2, ',', '.') }}</span>
            <span>{{ number_format($item->subtotal, 2, ',', '.') }}</span>
        </div>
    @endforeach

    <p class="receipt-line">--------------------------------</p>

    <div class="receipt-row">
        <span>Subtotal:</span>
        <span>{{ number_format($sale->subtotal, 2, ',', '.') }} MT</span>
    </div>
    @if($sale->discount_amount > 0)
        <div class="receipt-row">
            <span>Desconto:</span>
            <span>{{ number_format($sale->discount_amount, 2, ',', '.') }} MT</span>
        </div>
    @endif
    <div class="receipt-row receipt-bold receipt-total">
        <span>TOTAL:</span>
        <span>{{ number_format($sale->total, 2, ',', '.') }} MT</span>
    </div>

    <p class="receipt-line">--------------------------------</p>

    <p>Pagamento: {{ ucfirst($sale->payment_method) }}</p>
    @if($sale->payment_method === 'dinheiro' && $sale->amount_received)
        <div class="receipt-row">
            <span>Recebido:</span>
            <span>{{ number_format($sale->amount_received, 2, ',', '.') }} MT</span>
        </div>
        <div class="receipt-row">
            <span>Troco:</span>
            <span>{{ number_format($sale->change_amount, 2, ',', '.') }} MT</span>
        </div>
    @elseif(in_array($sale->payment_method, ['mpesa', 'emola']))
        <div class="receipt-row">
            <span>Valor pago:</span>
            <span>{{ number_format($sale->total, 2, ',', '.') }} MT</span>
        </div>
    @endif

    <p class="receipt-line">--------------------------------</p>

    <p class="receipt-center">Obrigado pela preferência!</p>
    <p class="receipt-center">Conserve este recibo.</p>
</div>

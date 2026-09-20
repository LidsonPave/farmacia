<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:dinheiro,mpesa,emola,cartao,outro'],
            'discount_type' => ['nullable', 'in:none,fixed,percentage'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'amount_received' => ['required_if:payment_method,dinheiro', 'nullable', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items' => 'itens',
            'payment_method' => 'método de pagamento',
            'discount_type' => 'tipo de desconto',
            'discount_value' => 'valor do desconto',
            'amount_received' => 'valor recebido',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $items = $this->input('items', []);
            $subtotal = 0;

            foreach ($items as $index => $item) {
                $medicine = Medicine::find($item['medicine_id'] ?? null);

                if (! $medicine) {
                    continue;
                }

                $quantity = (int) ($item['quantity'] ?? 0);
                $subtotal += $medicine->sale_price * $quantity;

                if ($quantity > $medicine->stock_quantity) {
                    $validator->errors()->add(
                        "items.{$index}.quantity",
                        "Stock insuficiente para {$medicine->name}. Stock atual: {$medicine->stock_quantity}."
                    );
                }
            }

            $discountType = $this->input('discount_type', 'none');
            $discountValue = (float) $this->input('discount_value', 0);

            if ($discountType === 'percentage' && $discountValue > 100) {
                $validator->errors()->add('discount_value', 'O desconto percentual nao pode ultrapassar 100%.');
            }

            if ($discountType === 'fixed' && $discountValue > $subtotal) {
                $validator->errors()->add('discount_value', 'O desconto nao pode ser superior ao subtotal da venda.');
            }

            if ($this->input('payment_method') === 'dinheiro') {
                $discountAmount = match ($discountType) {
                    'fixed' => $discountValue,
                    'percentage' => round($subtotal * ($discountValue / 100), 2),
                    default => 0,
                };

                $total = $subtotal - $discountAmount;
                $amountReceived = (float) $this->input('amount_received', 0);

                if ($amountReceived < $total) {
                    $validator->errors()->add('amount_received', 'O valor recebido nao pode ser inferior ao total da venda.');
                }
            }
        });
    }
}

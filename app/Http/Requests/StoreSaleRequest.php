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

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:dinheiro,mpesa,emola,cartao,outro'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'items' => 'itens',
            'payment_method' => 'método de pagamento',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $items = $this->input('items', []);

            foreach ($items as $index => $item) {
                $medicine = Medicine::find($item['medicine_id'] ?? null);

                if (! $medicine) {
                    continue;
                }

                $quantity = (int) ($item['quantity'] ?? 0);

                if ($quantity > $medicine->stock_quantity) {
                    $validator->errors()->add(
                        "items.{$index}.quantity",
                        "Stock insuficiente para {$medicine->name}. Stock atual: {$medicine->stock_quantity}."
                    );
                }
            }
        });
    }
}

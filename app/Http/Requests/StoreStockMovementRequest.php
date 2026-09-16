<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreStockMovementRequest extends FormRequest
{
    /**
     * Motivos válidos para cada tipo de movimentação manual.
     * "venda" fica de fora propositadamente: só é gerada automaticamente
     * pelo futuro módulo de Vendas, nunca via este formulário.
     *
     * @var array<string, array<int, string>>
     */
    private const VALID_REASONS_BY_TYPE = [
        'entrada' => ['compra', 'devolucao', 'ajuste'],
        'saida' => ['perda', 'ajuste'],
    ];

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
            'medicine_id' => ['required', 'exists:medicines,id'],
            'type' => ['required', 'in:entrada,saida'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'in:compra,ajuste,devolucao,perda'],
            'reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'medicine_id' => 'medicamento',
            'type' => 'tipo',
            'quantity' => 'quantidade',
            'reason' => 'motivo',
            'reference' => 'referência',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('type');
            $reason = $this->input('reason');

            if ($type && $reason) {
                $allowedReasons = self::VALID_REASONS_BY_TYPE[$type] ?? [];

                if (! in_array($reason, $allowedReasons, true)) {
                    $validator->errors()->add(
                        'reason',
                        'Este motivo não é válido para o tipo de movimentação selecionado.'
                    );
                }
            }

            if ($type !== 'saida') {
                return;
            }

            $medicine = Medicine::find($this->input('medicine_id'));

            if (! $medicine) {
                return;
            }

            $quantity = (int) $this->input('quantity');

            if ($quantity > $medicine->stock_quantity) {
                $validator->errors()->add(
                    'quantity',
                    "Stock insuficiente. Stock atual: {$medicine->stock_quantity}."
                );
            }
        });
    }
}

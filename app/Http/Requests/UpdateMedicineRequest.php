<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicineRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('medicines', 'code')->ignore($this->route('medicine')),
            ],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'categoria',
            'name' => 'nome',
            'code' => 'código',
            'purchase_price' => 'preço de compra',
            'sale_price' => 'preço de venda',
            'stock_quantity' => 'quantidade em stock',
            'minimum_stock' => 'stock mínimo',
            'expiry_date' => 'validade',
        ];
    }
}

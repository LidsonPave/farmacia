<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'type',
        'quantity',
        'batch_number',
        'batch_expiry_date',
        'supplier_id',
        'reason',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'batch_expiry_date' => 'date',
        ];
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Regista uma movimentação de stock e atualiza a quantidade do medicamento
     * de forma atómica. Ponto único de entrada para qualquer alteração de stock,
     * reutilizável por Movimentações (manual) e Vendas (automático).
     *
     * @throws RuntimeException se a saída deixar o stock negativo (RN02).
     */
    public static function registerMovement(array $data): self
    {
        return DB::transaction(function () use ($data) {
            $medicine = Medicine::lockForUpdate()->findOrFail($data['medicine_id']);

            if ($data['type'] === 'entrada') {
                $medicine->increment('stock_quantity', $data['quantity']);
            } else {
                if ($data['quantity'] > $medicine->stock_quantity) {
                    throw new RuntimeException(
                        "Stock insuficiente para {$medicine->name}. Stock atual: {$medicine->stock_quantity}."
                    );
                }

                $medicine->decrement('stock_quantity', $data['quantity']);
            }

            return self::create([
                'medicine_id' => $data['medicine_id'],
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'batch_number' => $data['batch_number'] ?? null,
                'batch_expiry_date' => $data['batch_expiry_date'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'reason' => $data['reason'],
                'reference' => $data['reference'] ?? null,
            ]);
        });
    }
}

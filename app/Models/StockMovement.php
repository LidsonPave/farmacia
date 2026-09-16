<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'type',
        'quantity',
        'reason',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
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

    /**
     * Regista uma movimentação de stock e atualiza a quantidade do medicamento
     * de forma atómica. Ponto único de entrada para qualquer alteração de stock,
     * reutilizável por Movimentações (manual) e Vendas (automático).
     */
    public static function registerMovement(array $data): self
    {
        return DB::transaction(function () use ($data) {
            $medicine = Medicine::lockForUpdate()->findOrFail($data['medicine_id']);

            if ($data['type'] === 'entrada') {
                $medicine->increment('stock_quantity', $data['quantity']);
            } else {
                $medicine->decrement('stock_quantity', $data['quantity']);
            }

            return self::create([
                'medicine_id' => $data['medicine_id'],
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'reason' => $data['reason'],
                'reference' => $data['reference'] ?? null,
            ]);
        });
    }
}

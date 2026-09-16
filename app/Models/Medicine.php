<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    /**
     * Número de dias antes da validade para considerar "próximo da validade".
     * Valor definido em conjunto com a farmácia; ajustar aqui se necessário.
     */
    public const EXPIRY_WARNING_DAYS = 60;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'minimum_stock',
        'expiry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'minimum_stock' => 'integer',
            'expiry_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }

    public function isExpired(): bool
    {
        if (! $this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    public function isNearExpiry(): bool
    {
        if (! $this->expiry_date || $this->isExpired()) {
            return false;
        }

        return $this->expiry_date->lte(now()->addDays(self::EXPIRY_WARNING_DAYS));
    }

    public function stockStatusLabel(): string
    {
        return $this->isLowStock() ? 'Stock Baixo' : 'Normal';
    }

    public function expiryStatusLabel(): string
    {
        if (! $this->expiry_date) {
            return 'Sem validade';
        }

        if ($this->isExpired()) {
            return 'Expirado';
        }

        if ($this->isNearExpiry()) {
            return 'Próximo da Validade';
        }

        return 'Normal';
    }
}

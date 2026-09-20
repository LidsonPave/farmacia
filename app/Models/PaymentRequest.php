<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'phone',
        'amount',
        'payment_method',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PaymentRequest $paymentRequest) {
            $paymentRequest->reference = $paymentRequest->reference ?? (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}

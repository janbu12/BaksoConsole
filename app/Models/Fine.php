<?php

namespace App\Models;

use App\Enums\FineType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['rental_id', 'type', 'late_days', 'amount', 'reason', 'status', 'paid_at'])]
class Fine extends Model
{
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    protected function casts(): array
    {
        return [
            'type' => FineType::class,
            'late_days' => 'integer',
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }
}

<?php

namespace App\Models;

use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\DeliveryType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['rental_id', 'type', 'method', 'address', 'contact_number', 'delivery_fee', 'courier_name', 'status', 'scheduled_at', 'completed_at'])]
class Delivery extends Model
{
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    protected function casts(): array
    {
        return [
            'type' => DeliveryType::class,
            'method' => DeliveryMethod::class,
            'delivery_fee' => 'decimal:2',
            'status' => DeliveryStatus::class,
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}

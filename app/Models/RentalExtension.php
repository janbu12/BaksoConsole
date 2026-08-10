<?php

namespace App\Models;

use App\Enums\ExtensionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['rental_id', 'requested_due_date', 'additional_days', 'additional_cost', 'reason', 'status', 'reviewed_by', 'reviewed_at', 'review_notes'])]
class RentalExtension extends Model
{
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected function casts(): array
    {
        return [
            'requested_due_date' => 'date',
            'additional_days' => 'integer',
            'additional_cost' => 'decimal:2',
            'status' => ExtensionStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'duration_days', 'controller_count', 'price', 'is_active'])]
class Combo extends Model
{
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'controller_count' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description', 'daily_price', 'max_players', 'status'])]
class Unit extends Model
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    protected function casts(): array
    {
        return [
            'daily_price' => 'decimal:2',
            'max_players' => 'integer',
            'status' => UnitStatus::class,
        ];
    }
}

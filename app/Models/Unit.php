<?php

namespace App\Models;

use App\Enums\FirmwareType;
use App\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'serial_number', 'model_number', 'firmware_type', 'description', 'daily_price', 'max_players', 'status'])]
class Unit extends Model
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Generate the next auto-increment unit code based on prefix (e.g. PS5 -> PS5-005, PS4 -> PS4-004, or UNT -> UNT-001)
     */
    public static function generateNextCode(?string $prefix = null): string
    {
        $prefix = strtoupper(trim($prefix ?: 'PS5'));
        if (!str_contains($prefix, '-')) {
            $prefixPattern = $prefix . '-';
        } else {
            $prefixPattern = $prefix;
        }

        $latestUnit = static::where('code', 'LIKE', $prefixPattern . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($latestUnit && preg_match('/' . preg_quote($prefixPattern, '/') . '(\d+)/', $latestUnit->code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            // Count existing units with similar code to prevent collision
            $count = static::where('code', 'LIKE', $prefixPattern . '%')->count();
            $nextNumber = $count + 1;
        }

        $code = $prefixPattern . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Ensure uniqueness just in case
        while (static::where('code', $code)->exists()) {
            $nextNumber++;
            $code = $prefixPattern . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    protected function casts(): array
    {
        return [
            'daily_price' => 'decimal:2',
            'max_players' => 'integer',
            'status' => UnitStatus::class,
            'firmware_type' => FirmwareType::class,
        ];
    }
}

<?php

namespace App\Domain\Rentals;

use Carbon\CarbonImmutable;

final class FineCalculator
{
    public static function lateDays(string $dueDate, string $returnedDate): int
    {
        return max(0, CarbonImmutable::parse($dueDate)->startOfDay()->diffInDays(CarbonImmutable::parse($returnedDate)->startOfDay(), false));
    }

    public static function amount(int $lateDays, float $dailyFine): float
    {
        return round(max(0, $lateDays) * max(0, $dailyFine), 2);
    }
}

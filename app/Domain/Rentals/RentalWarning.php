<?php

namespace App\Domain\Rentals;

use Carbon\CarbonImmutable;

final class RentalWarning
{
    public static function forDueDate(string|CarbonImmutable $dueDate, ?CarbonImmutable $today = null): string
    {
        $due = $dueDate instanceof CarbonImmutable ? $dueDate : CarbonImmutable::parse($dueDate);
        $today ??= CarbonImmutable::today();
        $remaining = $today->startOfDay()->diffInDays($due->startOfDay(), false);

        return $remaining < 0 ? 'overdue' : ($remaining <= 1 ? 'ending_soon' : 'safe');
    }
}

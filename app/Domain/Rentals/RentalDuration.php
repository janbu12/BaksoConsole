<?php

namespace App\Domain\Rentals;

use Carbon\CarbonImmutable;
use DomainException;

final class RentalDuration
{
    public static function days(string|CarbonImmutable $start, string|CarbonImmutable $end): int
    {
        $start = $start instanceof CarbonImmutable ? $start : CarbonImmutable::parse($start);
        $end = $end instanceof CarbonImmutable ? $end : CarbonImmutable::parse($end);
        if ($end->startOfDay()->lessThan($start->startOfDay())) {
            throw new DomainException('Tanggal selesai tidak boleh sebelum tanggal mulai.');
        }

        return $start->startOfDay()->diffInDays($end->startOfDay()) + 1;
    }
}

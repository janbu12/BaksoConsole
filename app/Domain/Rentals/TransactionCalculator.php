<?php

namespace App\Domain\Rentals;

final class TransactionCalculator
{
    public static function total(float $rental, float $fine = 0, float $delivery = 0, float $discount = 0): float
    {
        return round(max(0, $rental) + max(0, $fine) + max(0, $delivery) - max(0, $discount), 2);
    }
}

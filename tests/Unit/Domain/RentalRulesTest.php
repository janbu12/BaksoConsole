<?php

use App\Domain\Loyalty\BaksoRank;
use App\Domain\Rentals\FineCalculator;
use App\Domain\Rentals\RentalDuration;
use App\Domain\Rentals\RentalWarning;
use App\Domain\Rentals\TransactionCalculator;
use Carbon\CarbonImmutable;

it('calculates inclusive rental days and rejects reversed dates', function () {
    expect(RentalDuration::days('2026-08-10', '2026-08-14'))->toBe(5);
    expect(fn () => RentalDuration::days('2026-08-14', '2026-08-10'))->toThrow(DomainException::class);
});

it('derives rental warnings by calendar day', function () {
    $today = CarbonImmutable::parse('2026-08-10');
    expect(RentalWarning::forDueDate('2026-08-14', $today))->toBe('safe')
        ->and(RentalWarning::forDueDate('2026-08-11', $today))->toBe('ending_soon')
        ->and(RentalWarning::forDueDate('2026-08-09', $today))->toBe('overdue');
});

it('calculates fines totals and rank boundaries', function () {
    expect(FineCalculator::lateDays('2026-08-10', '2026-08-13'))->toBe(3)
        ->and(FineCalculator::amount(3, 10_000))->toBe(30_000.0)
        ->and(TransactionCalculator::total(100_000, 30_000, 15_000, 5_000))->toBe(140_000.0)
        ->and(BaksoRank::fromDays(5)['name'])->toBe('Bakso Rookie')
        ->and(BaksoRank::fromDays(6)['name'])->toBe('Bakso Player')
        ->and(BaksoRank::fromDays(16)['name'])->toBe('Bakso Pro')
        ->and(BaksoRank::fromDays(31)['name'])->toBe('Bakso Legend');
});

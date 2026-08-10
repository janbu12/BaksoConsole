<?php

namespace App\Application\Rentals;

use App\Application\Transactions\RecalculateTransaction;
use App\Domain\Rentals\FineCalculator;
use App\Enums\FineType;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Fine;
use App\Models\Rental;
use DomainException;
use Illuminate\Support\Facades\DB;

class ProcessReturn
{
    public function __construct(private RecalculateTransaction $recalculate) {}

    public function handle(Rental $rental, string $returnedAt, float $dailyFine = 0, ?string $notes = null): Rental
    {
        return DB::transaction(function () use ($rental, $returnedAt, $dailyFine, $notes) {
            $rental = Rental::query()->lockForUpdate()->findOrFail($rental->id);
            if (! in_array($rental->status, [RentalStatus::Active, RentalStatus::Overdue], true)) {
                throw new DomainException('Rental ini tidak dapat diproses kembali.');
            }
            $lateDays = FineCalculator::lateDays($rental->due_date->toDateString(), $returnedAt);
            if ($lateDays > 0 && $dailyFine > 0) {
                Fine::create(['rental_id' => $rental->id, 'type' => FineType::Late, 'late_days' => $lateDays, 'amount' => FineCalculator::amount($lateDays, $dailyFine), 'reason' => 'Denda keterlambatan', 'status' => PaymentStatus::Pending]);
            }
            $rental->update(['status' => RentalStatus::Returned, 'returned_at' => $returnedAt, 'return_notes' => $notes]);
            $rental->unit()->update(['status' => UnitStatus::Available]);
            $this->recalculate->handle($rental);

            return $rental->fresh();
        });
    }
}

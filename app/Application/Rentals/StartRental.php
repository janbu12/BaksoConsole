<?php

namespace App\Application\Rentals;

use App\Domain\Rentals\RentalDuration;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\Combo;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Queries\AvailableUnitQuery;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StartRental
{
    public function __construct(private AvailableUnitQuery $availability) {}

    public function handle(User $user, Unit $unit, string $start, string $end, ?Booking $booking = null, ?Combo $combo = null): Rental
    {
        $duration = RentalDuration::days($start, $end);
        if ($duration > 5) {
            throw new DomainException('Durasi sewa awal maksimal 5 hari.');
        }

        return DB::transaction(function () use ($user, $unit, $start, $end, $booking, $combo, $duration) {
            $unit = Unit::query()->lockForUpdate()->findOrFail($unit->id);
            if ($user->rentals()->whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])->count() >= 2) {
                throw new DomainException('Maksimal dua unit aktif per anggota.');
            }
            if (! $this->availability->check($unit, $start, $end, $booking)) {
                throw new DomainException('Unit tidak tersedia untuk disewa.');
            }
            $subtotal = $combo ? (float) $combo->price : (float) $unit->daily_price * $duration;
            $rental = Rental::create(['rental_code' => 'RNT-'.Str::upper(Str::random(10)), 'user_id' => $user->id, 'unit_id' => $unit->id, 'booking_id' => $booking?->id, 'combo_id' => $combo?->id, 'start_date' => $start, 'due_date' => $end, 'duration_days' => $duration, 'daily_price' => $unit->daily_price, 'subtotal' => $subtotal, 'status' => RentalStatus::Active]);
            $unit->update(['status' => UnitStatus::Rented]);
            $booking?->update(['status' => BookingStatus::Completed]);
            Transaction::create(['invoice_number' => 'INV-'.Str::upper(Str::random(10)), 'rental_id' => $rental->id, 'user_id' => $user->id, 'rental_amount' => $subtotal, 'total_amount' => $subtotal, 'status' => PaymentStatus::Pending]);

            return $rental->load('transaction');
        });
    }
}

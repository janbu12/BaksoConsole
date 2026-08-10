<?php

namespace App\Application\Bookings;

use App\Domain\Rentals\RentalDuration;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Unit;
use App\Models\User;
use App\Queries\AvailableUnitQuery;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateBooking
{
    public function __construct(private AvailableUnitQuery $availability) {}

    public function handle(User $user, Unit $unit, string $start, string $end, ?string $notes = null): Booking
    {
        $duration = RentalDuration::days($start, $end);
        if ($duration > 5) {
            throw new DomainException('Durasi booking maksimal 5 hari sesuai ketentuan.');
        }

        return DB::transaction(function () use ($user, $unit, $start, $end, $notes, $duration) {
            $unit = Unit::query()->lockForUpdate()->findOrFail($unit->id);
            if (! $this->availability->check($unit, $start, $end)) {
                throw new DomainException('Unit tidak tersedia pada jadwal yang dipilih.');
            }

            return Booking::create([
                'booking_code' => 'BKG-' . Str::upper(Str::random(10)),
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'start_date' => $start,
                'end_date' => $end,
                'duration_days' => $duration,
                'status' => BookingStatus::Pending,
                'notes' => $notes,
            ]);
        });
    }
}

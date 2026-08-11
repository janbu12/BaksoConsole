<?php

namespace App\Application\Bookings;

use App\Domain\Rentals\RentalDuration;
use App\Application\Rentals\StartRental;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use App\Queries\AvailableUnitQuery;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateBooking
{
    public function __construct(
        private AvailableUnitQuery $availability,
        private StartRental $startRental
    ) {}

    public function handle(
        User $user,
        Unit $unit,
        string $start,
        string $end,
        ?string $notes = null,
        string $deliveryMethod = 'pickup',
        ?string $deliveryAddress = null,
        ?string $contactNumber = null,
        ?array $requestedGames = null
    ): Rental {
        $duration = RentalDuration::days($start, $end);
        if ($duration > 5) {
            throw new DomainException('Durasi booking maksimal 5 hari sesuai ketentuan.');
        }

        return DB::transaction(function () use (
            $user, $unit, $start, $end, $notes, $duration,
            $deliveryMethod, $deliveryAddress, $contactNumber, $requestedGames
        ) {
            $unit = Unit::query()->lockForUpdate()->findOrFail($unit->id);
            if (! $this->availability->check($unit, $start, $end)) {
                throw new DomainException('Unit tidak tersedia pada jadwal yang dipilih.');
            }

            $fee = $deliveryMethod === 'delivery' ? 15000 : 0;

            $booking = Booking::create([
                'booking_code' => 'BKG-' . Str::upper(Str::random(10)),
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'start_date' => $start,
                'end_date' => $end,
                'duration_days' => $duration,
                'status' => BookingStatus::Pending,
                'notes' => $notes,
                'delivery_method' => $deliveryMethod,
                'delivery_address' => $deliveryMethod === 'delivery' ? ($deliveryAddress ?: $user->profile?->address) : null,
                'contact_number' => $deliveryMethod === 'delivery' ? ($contactNumber ?: $user->profile?->phone) : null,
                'delivery_fee' => $fee,
                'requested_games' => $requestedGames,
            ]);

            return $this->startRental->handle($user, $unit, $start, $end, $booking);
        });
    }
}

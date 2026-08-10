<?php

namespace App\Queries;

use App\Enums\BookingStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\Unit;

class AvailableUnitQuery
{
    public function check(Unit $unit, string $start, string $end, ?Booking $ignoreBooking = null): bool
    {
        if ($unit->status === UnitStatus::Maintenance) {
            return false;
        }
        $overlap = fn ($query) => $query->whereDate('start_date', '<=', $end)->whereDate($query->getModel() instanceof Booking ? 'end_date' : 'due_date', '>=', $start);
        $booked = $unit->bookings()->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->when($ignoreBooking, fn ($query) => $query->whereKeyNot($ignoreBooking->id))->where($overlap)->exists();
        $rented = $unit->rentals()->whereIn('status', [RentalStatus::Pending, RentalStatus::Active, RentalStatus::Overdue])->where($overlap)->exists();

        return ! $booked && ! $rented;
    }
}

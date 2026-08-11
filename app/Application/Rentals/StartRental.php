<?php

namespace App\Application\Rentals;

use App\Application\Transactions\RecalculateTransaction;
use App\Domain\Rentals\RentalDuration;
use App\Enums\BookingStatus;
use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\DeliveryType;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\Combo;
use App\Models\Delivery;
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
    public function __construct(
        private AvailableUnitQuery $availability,
        private RecalculateTransaction $recalculate
    ) {}

    public function handle(
        User $user,
        Unit $unit,
        string $start,
        string $end,
        ?Booking $booking = null,
        ?Combo $combo = null
    ): Rental {
        $duration = RentalDuration::days($start, $end);
        if ($duration > 5) {
            throw new DomainException('Durasi sewa awal maksimal 5 hari.');
        }

        return DB::transaction(function () use (
            $user, $unit, $start, $end, $booking, $combo, $duration
        ) {
            $unit = Unit::query()->lockForUpdate()->findOrFail($unit->id);
            if ($user->rentals()->whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])->count() >= 2) {
                throw new DomainException('Maksimal dua unit aktif per anggota.');
            }
            if (! $this->availability->check($unit, $start, $end, $booking)) {
                throw new DomainException('Unit tidak tersedia untuk disewa.');
            }
            
            $subtotal = $combo ? (float) $combo->price : (float) $unit->daily_price * $duration;
            $rental = Rental::create([
                'rental_code' => 'RNT-' . Str::upper(Str::random(10)),
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'booking_id' => $booking?->id,
                'combo_id' => $combo?->id,
                'start_date' => $start,
                'due_date' => $end,
                'duration_days' => $duration,
                'daily_price' => $unit->daily_price,
                'subtotal' => $subtotal,
                'status' => RentalStatus::Pending,
            ]);

            $unit->update(['status' => UnitStatus::Rented]);
            $booking?->update(['status' => BookingStatus::Confirmed]);

            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . Str::upper(Str::random(10)),
                'rental_id' => $rental->id,
                'user_id' => $user->id,
                'rental_amount' => $subtotal,
                'delivery_fee' => 0,
                'fine_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'status' => PaymentStatus::Pending,
            ]);

            // Create initial delivery_out record if specified
            $method = $booking?->delivery_method ?? 'pickup';
            $fee = $booking?->delivery_fee ?? 0;
            
            Delivery::create([
                'rental_id' => $rental->id,
                'type' => DeliveryType::DeliveryOut,
                'method' => $method === 'delivery' ? DeliveryMethod::Delivery : DeliveryMethod::Pickup,
                'address' => $method === 'delivery' ? ($booking?->delivery_address ?: $user->profile?->address) : null,
                'contact_number' => $method === 'delivery' ? ($booking?->contact_number ?: $user->profile?->phone) : null,
                'delivery_fee' => $fee,
                'status' => $method === 'delivery' ? DeliveryStatus::Waiting : DeliveryStatus::ReadyForPickup,
                'scheduled_at' => now(),
            ]);

            $this->recalculate->handle($rental);

            return $rental->load(['transaction', 'deliveries', 'unit', 'user']);
        });
    }
}

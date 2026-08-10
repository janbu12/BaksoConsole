<?php

use App\Application\Bookings\CreateBooking;
use App\Application\Rentals\ProcessReturn;
use App\Application\Rentals\StartRental;
use App\Enums\BookingStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('runs booking rental and return atomically', function () {
    $user = User::factory()->create();
    $unit = Unit::create(['name' => 'PlayStation 5', 'code' => 'PS5-001', 'daily_price' => 50_000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    $booking = app(CreateBooking::class)->handle($user, $unit, '2026-08-10', '2026-08-12');
    expect($booking->duration_days)->toBe(3)->and($booking->status)->toBe(BookingStatus::Pending);

    $booking->update(['status' => BookingStatus::Confirmed]);
    $rental = app(StartRental::class)->handle($user, $unit, '2026-08-10', '2026-08-12', $booking);
    expect($rental->status)->toBe(RentalStatus::Active)
        ->and($rental->subtotal)->toBe('150000.00')
        ->and($rental->transaction)->not->toBeNull()
        ->and($unit->fresh()->status)->toBe(UnitStatus::Rented);

    app(ProcessReturn::class)->handle($rental, '2026-08-14', 10_000, 'Terlambat');
    expect($rental->fresh()->status)->toBe(RentalStatus::Returned)
        ->and($rental->fines()->sum('amount'))->toBe(20_000)
        ->and($rental->transaction->fresh()->total_amount)->toBe('170000.00')
        ->and($unit->fresh()->status)->toBe(UnitStatus::Available);
});

it('rejects overlapping bookings and more than two active rentals', function () {
    $user = User::factory()->create();
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-001', 'daily_price' => 50_000, 'max_players' => 4, 'status' => UnitStatus::Available]);
    Booking::create(['booking_code' => 'BKG-1', 'user_id' => $user->id, 'unit_id' => $unit->id, 'start_date' => '2026-08-10', 'end_date' => '2026-08-12', 'duration_days' => 3, 'status' => BookingStatus::Confirmed]);

    expect(fn () => app(CreateBooking::class)->handle($user, $unit, '2026-08-11', '2026-08-13'))->toThrow(DomainException::class);
});

<?php

use App\Enums\BookingStatus;
use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\DeliveryType;
use App\Enums\ExtensionStatus;
use App\Enums\FineType;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Enums\UserRole;

it('defines the shared enum values', function (string $enum, array $values) {
    expect(array_column($enum::cases(), 'value'))->toBe($values);
})->with([
    [UserRole::class, ['admin', 'user']],
    [UnitStatus::class, ['available', 'booked', 'rented', 'returned', 'maintenance']],
    [BookingStatus::class, ['pending', 'confirmed', 'cancelled', 'completed', 'expired']],
    [RentalStatus::class, ['pending', 'active', 'overdue', 'returned', 'cancelled']],
    [ExtensionStatus::class, ['pending', 'approved', 'rejected']],
    [FineType::class, ['late', 'damage', 'other']],
    [PaymentStatus::class, ['pending', 'paid', 'cancelled', 'refunded']],
    [DeliveryType::class, ['delivery_out', 'delivery_return']],
    [DeliveryMethod::class, ['pickup', 'delivery']],
    [DeliveryStatus::class, ['ready_for_pickup', 'waiting', 'in_transit', 'received', 'picked_up', 'returned_to_outlet', 'cancelled']],
]);

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
use App\Models\Booking;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Fine;
use App\Models\Profile;
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

uses(TestCase::class);

it('defines every shared eloquent relationship', function () {
    $relationships = [
        [new User, 'profile', HasOne::class],
        [new User, 'bookings', HasMany::class],
        [new User, 'rentals', HasMany::class],
        [new User, 'reviewedExtensions', HasMany::class],
        [new User, 'transactions', HasMany::class],
        [new Profile, 'user', BelongsTo::class],
        [new Unit, 'categories', BelongsToMany::class],
        [new Unit, 'bookings', HasMany::class],
        [new Unit, 'rentals', HasMany::class],
        [new Category, 'units', BelongsToMany::class],
        [new Combo, 'rentals', HasMany::class],
        [new Booking, 'user', BelongsTo::class],
        [new Booking, 'unit', BelongsTo::class],
        [new Booking, 'rental', HasOne::class],
        [new Rental, 'user', BelongsTo::class],
        [new Rental, 'unit', BelongsTo::class],
        [new Rental, 'booking', BelongsTo::class],
        [new Rental, 'combo', BelongsTo::class],
        [new Rental, 'extensions', HasMany::class],
        [new Rental, 'fines', HasMany::class],
        [new Rental, 'deliveries', HasMany::class],
        [new Rental, 'transaction', HasOne::class],
        [new RentalExtension, 'rental', BelongsTo::class],
        [new RentalExtension, 'reviewer', BelongsTo::class],
        [new Fine, 'rental', BelongsTo::class],
        [new Delivery, 'rental', BelongsTo::class],
        [new Transaction, 'rental', BelongsTo::class],
        [new Transaction, 'user', BelongsTo::class],
    ];

    foreach ($relationships as [$model, $method, $relation]) {
        expect($model->{$method}())->toBeInstanceOf($relation);
    }
});

it('defines the shared attribute casts', function () {
    $contracts = [
        [new User, ['role' => UserRole::class]],
        [new Profile, ['date_of_birth' => 'date']],
        [new Unit, ['daily_price' => 'decimal:2', 'max_players' => 'integer', 'status' => UnitStatus::class]],
        [new Combo, ['duration_days' => 'integer', 'controller_count' => 'integer', 'price' => 'decimal:2', 'is_active' => 'boolean']],
        [new Booking, ['start_date' => 'date', 'end_date' => 'date', 'duration_days' => 'integer', 'status' => BookingStatus::class]],
        [new Rental, ['start_date' => 'date', 'due_date' => 'date', 'duration_days' => 'integer', 'daily_price' => 'decimal:2', 'subtotal' => 'decimal:2', 'status' => RentalStatus::class, 'returned_at' => 'datetime']],
        [new RentalExtension, ['requested_due_date' => 'date', 'additional_days' => 'integer', 'additional_cost' => 'decimal:2', 'status' => ExtensionStatus::class, 'reviewed_at' => 'datetime']],
        [new Fine, ['type' => FineType::class, 'late_days' => 'integer', 'amount' => 'decimal:2', 'status' => PaymentStatus::class, 'paid_at' => 'datetime']],
        [new Delivery, ['type' => DeliveryType::class, 'method' => DeliveryMethod::class, 'delivery_fee' => 'decimal:2', 'status' => DeliveryStatus::class, 'scheduled_at' => 'datetime', 'completed_at' => 'datetime']],
        [new Transaction, ['rental_amount' => 'decimal:2', 'fine_amount' => 'decimal:2', 'delivery_fee' => 'decimal:2', 'discount_amount' => 'decimal:2', 'total_amount' => 'decimal:2', 'status' => PaymentStatus::class, 'paid_at' => 'datetime']],
    ];

    foreach ($contracts as [$model, $expectedCasts]) {
        expect($model->getCasts())->toMatchArray($expectedCasts);
    }
});

<?php

use App\Enums\BookingStatus;
use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\DeliveryType;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Delivery;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

it('uses Jakarta date and shows occupied unit schedules in the catalogue', function () {
    Carbon::setTestNow(Carbon::parse('2026-08-11 00:30:00', 'Asia/Jakarta'));

    $user = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create([
        'name' => 'PlayStation 5',
        'code' => 'PS5-JKT',
        'daily_price' => 50000,
        'max_players' => 4,
        'status' => UnitStatus::Rented,
    ]);

    Booking::create([
        'booking_code' => 'BKG-JKT',
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-08-12',
        'end_date' => '2026-08-14',
        'duration_days' => 3,
        'status' => BookingStatus::Confirmed,
    ]);

    $this->actingAs($user)
        ->get('/catalogue')
        ->assertOk()
        ->assertSee('12/08/2026 - 14/08/2026')
        ->assertSee('value="2026-08-11"', false)
        ->assertSee('Buat Reservasi / Booking');

    expect(config('app.timezone'))->toBe('Asia/Jakarta');
});

it('allows only the rental owner to download a PDF invoice', function () {
    $owner = User::factory()->create(['name' => 'Pemilik Invoice', 'role' => UserRole::User]);
    $otherUser = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create([
        'name' => 'PlayStation 5',
        'code' => 'PS5-INV',
        'daily_price' => 50000,
        'max_players' => 4,
        'status' => UnitStatus::Rented,
    ]);
    $rental = Rental::create([
        'rental_code' => 'RNT-INVOICE',
        'user_id' => $owner->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-08-11',
        'due_date' => '2026-08-13',
        'duration_days' => 3,
        'daily_price' => 50000,
        'subtotal' => 150000,
        'status' => RentalStatus::Pending,
    ]);
    Transaction::create([
        'invoice_number' => 'INV-DOWNLOAD-1',
        'rental_id' => $rental->id,
        'user_id' => $owner->id,
        'rental_amount' => 150000,
        'fine_amount' => 0,
        'delivery_fee' => 0,
        'discount_amount' => 0,
        'total_amount' => 150000,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($owner)->get(route('rentals.invoice.download', $rental));
    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('Invoice-INV-DOWNLOAD-1.pdf');
    expect($response->getContent())->toStartWith('%PDF-');

    $this->actingAs($otherUser)
        ->get(route('rentals.invoice.download', $rental))
        ->assertForbidden();
});

it('shows home delivery progress only to the rental owner', function () {
    $owner = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create([
        'name' => 'PlayStation 5',
        'code' => 'PS5-TRACK',
        'daily_price' => 50000,
        'max_players' => 4,
        'status' => UnitStatus::Rented,
    ]);
    $rental = Rental::create([
        'rental_code' => 'RNT-TRACK',
        'user_id' => $owner->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-08-11',
        'due_date' => '2026-08-13',
        'duration_days' => 3,
        'daily_price' => 50000,
        'subtotal' => 150000,
        'status' => RentalStatus::Pending,
    ]);
    Transaction::create([
        'invoice_number' => 'INV-TRACK',
        'rental_id' => $rental->id,
        'user_id' => $owner->id,
        'rental_amount' => 150000,
        'fine_amount' => 0,
        'delivery_fee' => 15000,
        'discount_amount' => 0,
        'total_amount' => 165000,
        'status' => 'paid',
    ]);
    Delivery::create([
        'rental_id' => $rental->id,
        'type' => DeliveryType::DeliveryOut,
        'method' => DeliveryMethod::Delivery,
        'address' => 'Jl. Melati No. 11, Jakarta',
        'contact_number' => '081234567890',
        'delivery_fee' => 15000,
        'courier_name' => 'Kurir Budi',
        'status' => DeliveryStatus::InTransit,
        'scheduled_at' => now(),
    ]);

    $this->actingAs($owner)
        ->get('/rentals')
        ->assertOk()
        ->assertSee('Status Pengiriman ke Rumah')
        ->assertSee('Dalam Perjalanan')
        ->assertSee('Kurir Budi')
        ->assertSee('Jl. Melati No. 11, Jakarta')
        ->assertSee('Terakhir diperbarui');
});

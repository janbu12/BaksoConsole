<?php

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('handles simulation payment fallback when Midtrans is disabled', function () {
    Config::set('services.midtrans.server_key', null);

    $user = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-TEST', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    $rental = Rental::create([
        'rental_code' => 'RNT-PAY-1',
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'start_date' => today(),
        'due_date' => today()->addDay(),
        'duration_days' => 2,
        'daily_price' => 50000,
        'subtotal' => 100000,
        'status' => RentalStatus::Pending,
    ]);

    $transaction = Transaction::create([
        'invoice_number' => 'INV-PAY-1',
        'rental_id' => $rental->id,
        'user_id' => $user->id,
        'rental_amount' => 100000,
        'fine_amount' => 0,
        'delivery_fee' => 0,
        'discount_amount' => 0,
        'total_amount' => 100000,
        'status' => PaymentStatus::Pending,
    ]);

    // Pay route with Midtrans disabled should fallback to simulation
    $this->actingAs($user)->post("/rentals/{$rental->id}/pay")
        ->assertRedirect('/rentals');

    expect($transaction->fresh()->status)->toBe(PaymentStatus::Paid)
        ->and($transaction->fresh()->paid_at)->not->toBeNull();
});

it('creates Midtrans Snap redirect url when Midtrans is configured', function () {
    Config::set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY123');
    Config::set('services.midtrans.is_production', false);

    Http::fake([
        'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'mock-snap-token-xyz',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-snap-token-xyz',
        ], 201),
    ]);

    $user = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create(['name' => 'PS5 Disc', 'code' => 'PS5-SNAP', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    $rental = Rental::create([
        'rental_code' => 'RNT-SNAP-1',
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'start_date' => today(),
        'due_date' => today()->addDay(),
        'duration_days' => 2,
        'daily_price' => 50000,
        'subtotal' => 100000,
        'status' => RentalStatus::Pending,
    ]);

    $transaction = Transaction::create([
        'invoice_number' => 'INV-SNAP-1',
        'rental_id' => $rental->id,
        'user_id' => $user->id,
        'rental_amount' => 100000,
        'fine_amount' => 0,
        'delivery_fee' => 15000,
        'discount_amount' => 0,
        'total_amount' => 115000,
        'status' => PaymentStatus::Pending,
    ]);

    $response = $this->actingAs($user)->post("/rentals/{$rental->id}/pay");

    $response->assertRedirect('https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-snap-token-xyz');
});

it('processes Midtrans webhook notification and marks transaction as paid', function () {
    $serverKey = 'SB-Mid-server-TESTKEY123';
    Config::set('services.midtrans.server_key', $serverKey);

    $user = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-NOTIF', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    $rental = Rental::create([
        'rental_code' => 'RNT-NOTIF-1',
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'start_date' => today(),
        'due_date' => today()->addDay(),
        'duration_days' => 2,
        'daily_price' => 50000,
        'subtotal' => 100000,
        'status' => RentalStatus::Pending,
    ]);

    $transaction = Transaction::create([
        'invoice_number' => 'INV-NOTIF-100',
        'rental_id' => $rental->id,
        'user_id' => $user->id,
        'rental_amount' => 100000,
        'fine_amount' => 0,
        'delivery_fee' => 0,
        'discount_amount' => 0,
        'total_amount' => 100000,
        'status' => PaymentStatus::Pending,
    ]);

    $orderId = 'INV-NOTIF-100-' . time();
    $statusCode = '200';
    $grossAmount = '100000.00';
    $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    $webhookPayload = [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $signature,
        'transaction_status' => 'settlement',
        'payment_type' => 'qris',
    ];

    $this->postJson('/midtrans/notification', $webhookPayload)
        ->assertOk()
        ->assertJson(['status' => 'success']);

    expect($transaction->fresh()->status)->toBe(PaymentStatus::Paid)
        ->and($transaction->fresh()->notes)->toContain('Dibayar via Midtrans (qris)');
});

it('handles Midtrans return routes smoothly', function () {
    $this->get('/midtrans/finish')->assertRedirect('/rentals');
    $this->get('/midtrans/unfinish')->assertRedirect('/rentals');
    $this->get('/midtrans/error')->assertRedirect('/rentals');
});

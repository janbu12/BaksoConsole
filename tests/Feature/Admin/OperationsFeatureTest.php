<?php

use App\Enums\BookingStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an admin to manage members', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/members', [
        'name' => 'Member Baru',
        'email' => 'member@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '08123456789',
    ])->assertRedirect();

    $member = User::where('email', 'member@example.test')->firstOrFail();
    expect($member->profile->phone)->toBe('08123456789');

    $this->actingAs($admin)->put("/admin/members/{$member->id}", [
        'name' => 'Member Diubah',
        'email' => 'member@example.test',
        'phone' => '0899999999',
    ])->assertRedirect();

    expect($member->fresh()->name)->toBe('Member Diubah');
    $this->actingAs($admin)->delete("/admin/members/{$member->id}")->assertRedirect();
    $this->assertDatabaseMissing('users', ['id' => $member->id]);
});

it('supports booking cancellation and manual damage fines', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $member = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-X', 'daily_price' => 100000, 'max_players' => 4, 'status' => UnitStatus::Rented]);
    $booking = Booking::create(['booking_code' => 'BKG-X', 'user_id' => $member->id, 'unit_id' => $unit->id, 'start_date' => today(), 'end_date' => today()->addDay(), 'duration_days' => 2, 'status' => BookingStatus::Pending]);

    $this->actingAs($member)->delete("/bookings/{$booking->id}")->assertRedirect();
    expect($booking->fresh()->status)->toBe(BookingStatus::Cancelled);

    $rental = Rental::create(['rental_code' => 'RNT-X', 'user_id' => $member->id, 'unit_id' => $unit->id, 'start_date' => today(), 'due_date' => today()->addDay(), 'duration_days' => 2, 'daily_price' => 100000, 'subtotal' => 200000, 'status' => RentalStatus::Active]);
    Transaction::create(['invoice_number' => 'INV-X', 'rental_id' => $rental->id, 'user_id' => $member->id, 'rental_amount' => 200000, 'fine_amount' => 0, 'delivery_fee' => 0, 'discount_amount' => 0, 'total_amount' => 200000, 'status' => 'pending']);

    $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/fines", ['amount' => 50000, 'reason' => 'Stik rusak'])->assertRedirect();
    $this->assertDatabaseHas('fines', ['rental_id' => $rental->id, 'type' => 'damage', 'amount' => 50000]);
    expect((float) $rental->transaction->fresh()->total_amount)->toBe(250000.0);
});

it('shows admin analytics and printable rental history', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Rental Heatmap')->assertSee('Anggota paling aktif');
    $this->actingAs($admin)->get('/admin/history/print')->assertOk()->assertSee('Cetak Riwayat Rental');
});

it('serves all dedicated admin sidebar sub-pages correctly', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->get('/admin/units')->assertOk()->assertSee('Kelola Unit Konsol');
    $this->actingAs($admin)->get('/admin/categories')->assertOk()->assertSee('Kategori Platform');
    $this->actingAs($admin)->get('/admin/members')->assertOk()->assertSee('Manajemen Anggota');
    $this->actingAs($admin)->get('/admin/bookings')->assertOk()->assertSee('Antrean Serah Terima');
    $this->actingAs($admin)->get('/admin/returns')->assertOk()->assertSee('Pengembalian Unit');
    $this->actingAs($admin)->get('/admin/deliveries')->assertOk()->assertSee('Pickup & Delivery');
    $this->actingAs($admin)->get('/admin/history')->assertOk()->assertSee('Laporan Rekapitulasi');
});

it('supports auto-generated unit codes, hardware serial numbers, and installed games', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $game1 = \App\Models\Game::create(['name' => 'The Warriors', 'slug' => 'the-warriors', 'genre' => 'Action']);
    $game2 = \App\Models\Game::create(['name' => "Assassin's Creed Mirage", 'slug' => 'assassins-creed-mirage', 'genre' => 'Action']);

    // 1. Create unit with auto-generated code
    $this->actingAs($admin)->post('/admin/units', [
        'name' => 'PlayStation 5 Slim Special',
        'code' => '', // empty, should auto-generate
        'serial_number' => 'S01-9988776-Z',
        'model_number' => 'CFI-2018',
        'daily_price' => 50000,
        'max_players' => 4,
        'game_ids' => [$game1->id, $game2->id],
    ])->assertRedirect();

    $unit = Unit::where('serial_number', 'S01-9988776-Z')->firstOrFail();
    expect($unit->code)->toStartWith('PS5-')
        ->and($unit->model_number)->toBe('CFI-2018')
        ->and($unit->games)->toHaveCount(2);

    // 2. Add and delete game master
    $this->actingAs($admin)->post('/admin/games', [
        'name' => 'EA Sports F1 24',
        'genre' => 'Racing',
        'icon' => '🏎️',
    ])->assertRedirect();

    $newGame = \App\Models\Game::where('name', 'EA Sports F1 24')->firstOrFail();
    expect($newGame->genre)->toBe('Racing');

    $this->actingAs($admin)->delete("/admin/games/{$newGame->id}")->assertRedirect();
    $this->assertDatabaseMissing('games', ['id' => $newGame->id]);
});


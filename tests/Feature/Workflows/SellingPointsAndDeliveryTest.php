<?php

use App\Domain\Loyalty\BaksoRank;
use App\Domain\Rentals\RentalWarning;
use App\Domain\Rentals\SmartPickRecommender;
use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\DeliveryType;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use App\Queries\AdminDashboardQuery;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('evaluates SmartPick recommendations with match indicators and scoring', function () {
    $catPS5 = Category::create(['name' => 'PlayStation 5', 'slug' => 'playstation-5']);
    $unit1 = Unit::create(['name' => 'PS5 High End', 'code' => 'PS5-001', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);
    $unit1->categories()->sync([$catPS5->id]);

    $unit2 = Unit::create(['name' => 'PS4 Single', 'code' => 'PS4-001', 'daily_price' => 30000, 'max_players' => 1, 'status' => UnitStatus::Available]);

    $results = SmartPickRecommender::evaluate(collect([$unit1, $unit2]), 4, 3, 160000, $catPS5->id);

    expect($results->first()->id)->toBe($unit1->id)
        ->and($results->first()->smart_pick['is_best_match'])->toBeTrue()
        ->and($results->first()->smart_pick['badges'])->toContain('✓ Sesuai 4 Pemain')
        ->and($results->first()->smart_pick['badges'])->toContain('✓ Sesuai Durasi 3 Hari');
});

it('calculates Bakso Rank loyalty levels and progress accurately', function () {
    $rookie = BaksoRank::fromDays(3);
    expect($rookie['name'])->toBe('Bakso Rookie')
        ->and($rookie['next_rank'])->toBe('Bakso Player')
        ->and($rookie['days_needed'])->toBe(3);

    $player = BaksoRank::fromDays(10);
    expect($player['name'])->toBe('Bakso Player')
        ->and($player['next_rank'])->toBe('Bakso Pro');

    $pro = BaksoRank::fromDays(20);
    expect($pro['name'])->toBe('Bakso Pro')
        ->and($pro['next_rank'])->toBe('Bakso Legend');

    $legend = BaksoRank::fromDays(35);
    expect($legend['name'])->toBe('Bakso Legend')
        ->and($legend['next_rank'])->toBeNull()
        ->and($legend['progress_percent'])->toBe(100);
});

it('provides rich rental warning details for calendar countdown and danger levels', function () {
    $today = CarbonImmutable::parse('2026-08-10');

    $safe = RentalWarning::details('2026-08-14', $today);
    expect($safe['is_safe'])->toBeTrue()
        ->and($safe['remaining_days'])->toBe(4)
        ->and($safe['code'])->toBe('🟢 Aman');

    $warning = RentalWarning::details('2026-08-11', $today);
    expect($warning['is_warning'])->toBeTrue()
        ->and($warning['code'])->toBe('🟡 Segera Berakhir');

    $overdue = RentalWarning::details('2026-08-08', $today);
    expect($overdue['is_overdue'])->toBeTrue()
        ->and($overdue['remaining_days'])->toBe(-2)
        ->and($overdue['code'])->toBe('🔴 Terlambat');
});

it('supports pickup and delivery service creation and admin courier assignment', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $user = User::factory()->create(['role' => UserRole::User]);
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-DEL', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    $rental = Rental::create([
        'rental_code' => 'RNT-DEL-1',
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-08-10',
        'due_date' => '2026-08-13',
        'duration_days' => 4,
        'daily_price' => 50000,
        'subtotal' => 200000,
        'status' => 'active',
    ]);
    $rental->transaction()->create([
        'invoice_number' => 'INV-DEL-1',
        'user_id' => $user->id,
        'rental_amount' => 200000,
        'total_amount' => 200000,
        'status' => 'pending',
    ]);

    // Member chooses delivery for return
    $this->actingAs($user)->post("/rentals/{$rental->id}/deliveries", [
        'type' => 'delivery_return',
        'method' => 'delivery',
        'address' => 'Jl. Mawar No. 10',
        'contact_number' => '08123456789',
    ])->assertRedirect();

    $delivery = Delivery::where('rental_id', $rental->id)->where('type', 'delivery_return')->firstOrFail();
    expect($delivery->method)->toBe(DeliveryMethod::Delivery)
        ->and($delivery->delivery_fee)->toBe('15000.00');

    // Admin assigns courier and marks in_transit
    $this->actingAs($admin)->post("/admin/deliveries/{$delivery->id}", [
        'status' => DeliveryStatus::InTransit->value,
        'courier_name' => 'Kurir Doni',
        'delivery_fee' => 15000,
    ])->assertRedirect();

    expect($delivery->fresh()->courier_name)->toBe('Kurir Doni')
        ->and($delivery->fresh()->status)->toBe(DeliveryStatus::InTransit);
});

it('aggregates heatmap data and peak rental period correctly in admin insight', function () {
    $user = User::factory()->create();
    $unit = Unit::create(['name' => 'PS5', 'code' => 'PS5-H', 'daily_price' => 50000, 'max_players' => 4, 'status' => UnitStatus::Available]);

    Rental::create(['rental_code' => 'R-1', 'user_id' => $user->id, 'unit_id' => $unit->id, 'start_date' => '2026-08-10', 'due_date' => '2026-08-12', 'duration_days' => 3, 'daily_price' => 50000, 'subtotal' => 150000, 'status' => 'returned']);
    Rental::create(['rental_code' => 'R-2', 'user_id' => $user->id, 'unit_id' => $unit->id, 'start_date' => '2026-08-10', 'due_date' => '2026-08-13', 'duration_days' => 4, 'daily_price' => 50000, 'subtotal' => 200000, 'status' => 'returned']);

    $query = app(AdminDashboardQuery::class)->get();
    expect($query['peakDay']->start_date->toDateString())->toBe('2026-08-10')
        ->and($query['peakDay']->total)->toBe(2);
});

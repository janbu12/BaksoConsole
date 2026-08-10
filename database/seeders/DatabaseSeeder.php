<?php

namespace Database\Seeders;

use App\Application\Transactions\RecalculateTransaction;
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
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Seed Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@baksoconsole.com'],
            ['name' => 'Admin Bakso', 'password' => 'password', 'role' => UserRole::Admin]
        );
        $admin->profile()->updateOrCreate([], ['phone' => '081234567890', 'address' => 'Outlet Bakso Console HQ, Jakarta']);

        $member1 = User::updateOrCreate(
            ['email' => 'member@baksoconsole.com'],
            ['name' => 'Nable Gamer', 'password' => 'password', 'role' => UserRole::User]
        );
        $member1->profile()->updateOrCreate([], ['phone' => '081234567891', 'address' => 'Jl. Tebet Raya No. 45, Jakarta Selatan']);

        $member2 = User::updateOrCreate(
            ['email' => 'pro@baksoconsole.com'],
            ['name' => 'Mizan Pro Player', 'password' => 'password', 'role' => UserRole::User]
        );
        $member2->profile()->updateOrCreate([], ['phone' => '081298765432', 'address' => 'Jl. Kemang Timur No. 12, Jakarta Selatan']);

        $member3 = User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            ['name' => 'Budi Santoso', 'password' => 'password', 'role' => UserRole::User]
        );
        $member3->profile()->updateOrCreate([], ['phone' => '081311223344', 'address' => 'Jl. Fatmawati No. 8, Jakarta']);

        // 2. Seed Categories
        $categoriesList = ['PlayStation 4', 'PlayStation 5', 'Multiplayer', 'Family', 'Mabar', 'Action & RPG'];
        $categories = collect($categoriesList)->mapWithKeys(function ($name) {
            return [$name => Category::updateOrCreate(['slug' => str($name)->slug()], ['name' => $name, 'description' => "Kategori game & unit $name"])];
        });

        // 3. Seed Units
        $unitsData = [
            ['PlayStation 5', 'PS5-001', 50000, 4, UnitStatus::Available, ['PlayStation 5', 'Multiplayer', 'Mabar']],
            ['PlayStation 5', 'PS5-002', 50000, 4, UnitStatus::Rented, ['PlayStation 5', 'Multiplayer', 'Mabar']],
            ['PlayStation 5', 'PS5-003', 50000, 4, UnitStatus::Booked, ['PlayStation 5', 'Multiplayer', 'Family', 'Mabar']],
            ['PlayStation 5', 'PS5-004', 50000, 4, UnitStatus::Available, ['PlayStation 5', 'Action & RPG', 'Single Player']],
            ['PlayStation 4', 'PS4-001', 35000, 4, UnitStatus::Available, ['PlayStation 4', 'Multiplayer', 'Mabar', 'Family']],
            ['PlayStation 4', 'PS4-002', 35000, 2, UnitStatus::Available, ['PlayStation 4', 'Family']],
            ['PlayStation 4', 'PS4-003', 35000, 4, UnitStatus::Maintenance, ['PlayStation 4', 'Multiplayer']],
        ];

        $createdUnits = [];
        foreach ($unitsData as [$name, $code, $price, $players, $status, $cats]) {
            $unit = Unit::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'daily_price' => $price, 'max_players' => $players, 'status' => $status]
            );
            $syncIds = collect($cats)->map(fn ($c) => $categories[$c]->id ?? null)->filter()->all();
            $unit->categories()->sync($syncIds);
            $createdUnits[$code] = $unit;
        }

        // 4. Seed Combos
        Combo::updateOrCreate(
            ['slug' => 'bakso-mabar'],
            ['name' => 'Bakso Mabar', 'duration_days' => 3, 'controller_count' => 4, 'price' => 150000, 'is_active' => true]
        );
        Combo::updateOrCreate(
            ['slug' => 'bakso-family'],
            ['name' => 'Bakso Family', 'duration_days' => 2, 'controller_count' => 2, 'price' => 80000, 'is_active' => true]
        );
        Combo::updateOrCreate(
            ['slug' => 'bakso-weekend'],
            ['name' => 'Bakso Weekend', 'duration_days' => 2, 'controller_count' => 4, 'price' => 110000, 'is_active' => true]
        );

        // 5. Seed Historical Returned Rentals (to generate Bakso Rank & Heatmap data)
        $pastDates = [
            ['2026-08-01', '2026-08-03', 3, $member1, $createdUnits['PS5-001']],
            ['2026-08-02', '2026-08-04', 3, $member2, $createdUnits['PS4-001']],
            ['2026-08-03', '2026-08-06', 4, $member2, $createdUnits['PS5-002']],
            ['2026-08-04', '2026-08-08', 5, $member2, $createdUnits['PS5-001']],
            ['2026-08-05', '2026-08-08', 4, $member1, $createdUnits['PS4-002']],
            ['2026-08-05', '2026-08-07', 3, $member3, $createdUnits['PS5-004']],
            ['2026-08-06', '2026-08-10', 5, $member2, $createdUnits['PS5-003']],
            ['2026-08-07', '2026-08-09', 3, $member2, $createdUnits['PS4-001']],
        ];

        foreach ($pastDates as $idx => [$start, $due, $days, $user, $unit]) {
            $subtotal = $unit->daily_price * $days;
            $rental = Rental::updateOrCreate(
                ['rental_code' => 'RNT-PAST-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $user->id,
                    'unit_id' => $unit->id,
                    'start_date' => $start,
                    'due_date' => $due,
                    'returned_at' => $due,
                    'duration_days' => $days,
                    'daily_price' => $unit->daily_price,
                    'subtotal' => $subtotal,
                    'status' => RentalStatus::Returned,
                    'return_notes' => 'Kondisi unit lengkap dan mulus.',
                ]
            );

            $method = $idx % 2 === 0 ? DeliveryMethod::Delivery : DeliveryMethod::Pickup;
            $fee = $method === DeliveryMethod::Delivery ? 15000 : 0;

            Delivery::updateOrCreate(
                ['rental_id' => $rental->id, 'type' => DeliveryType::DeliveryOut],
                [
                    'method' => $method,
                    'address' => $user->profile?->address,
                    'contact_number' => $user->profile?->phone,
                    'delivery_fee' => $fee,
                    'courier_name' => $method === DeliveryMethod::Delivery ? 'Kurir Agus' : null,
                    'status' => DeliveryStatus::Received,
                    'scheduled_at' => $start,
                    'completed_at' => $start,
                ]
            );

            Transaction::updateOrCreate(
                ['rental_id' => $rental->id],
                [
                    'invoice_number' => 'INV-PAST-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'rental_amount' => $subtotal,
                    'fine_amount' => 0,
                    'delivery_fee' => $fee,
                    'discount_amount' => 0,
                    'total_amount' => $subtotal + $fee,
                    'status' => PaymentStatus::Paid,
                    'paid_at' => $due,
                ]
            );
        }

        // 6. Seed Active Rentals with various timer statuses:
        // Rental A: Safe (Mulai hari ini, due in 3 days)
        $rSafe = Rental::updateOrCreate(
            ['rental_code' => 'RNT-SAFE-001'],
            [
                'user_id' => $member1->id,
                'unit_id' => $createdUnits['PS5-002']->id,
                'start_date' => Carbon::today()->toDateString(),
                'due_date' => Carbon::today()->addDays(3)->toDateString(),
                'duration_days' => 4,
                'daily_price' => 50000,
                'subtotal' => 200000,
                'status' => RentalStatus::Active,
            ]
        );
        Delivery::updateOrCreate(
            ['rental_id' => $rSafe->id, 'type' => DeliveryType::DeliveryOut],
            [
                'method' => DeliveryMethod::Delivery,
                'address' => $member1->profile->address,
                'contact_number' => $member1->profile->phone,
                'delivery_fee' => 15000,
                'courier_name' => 'Kurir Budi',
                'status' => DeliveryStatus::Received,
                'scheduled_at' => now(),
            ]
        );
        Transaction::updateOrCreate(
            ['rental_id' => $rSafe->id],
            [
                'invoice_number' => 'INV-SAFE-001',
                'user_id' => $member1->id,
                'rental_amount' => 200000,
                'delivery_fee' => 15000,
                'fine_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 215000,
                'status' => PaymentStatus::Pending,
            ]
        );

        // 7. Seed Active Bookings
        Booking::updateOrCreate(
            ['booking_code' => 'BKG-DEMO-001'],
            [
                'user_id' => $member1->id,
                'unit_id' => $createdUnits['PS5-003']->id,
                'start_date' => Carbon::tomorrow()->toDateString(),
                'end_date' => Carbon::tomorrow()->addDays(2)->toDateString(),
                'duration_days' => 3,
                'status' => BookingStatus::Confirmed,
                'notes' => 'Siap diambil di outlet jam 13:00 WIB',
            ]
        );

        Booking::updateOrCreate(
            ['booking_code' => 'BKG-DEMO-002'],
            [
                'user_id' => $member3->id,
                'unit_id' => $createdUnits['PS4-001']->id,
                'start_date' => Carbon::today()->addDays(2)->toDateString(),
                'end_date' => Carbon::today()->addDays(4)->toDateString(),
                'duration_days' => 3,
                'status' => BookingStatus::Pending,
                'notes' => 'Minta diantar kurir ke alamat domisili',
            ]
        );
    }
}

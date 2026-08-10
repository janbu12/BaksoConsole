<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@baksoconsole.com'], ['name' => 'Admin Bakso', 'password' => 'password', 'role' => UserRole::Admin]);
        $admin->profile()->updateOrCreate([], ['phone' => '081234567890']);
        $member = User::updateOrCreate(['email' => 'member@baksoconsole.com'], ['name' => 'Member Demo', 'password' => 'password', 'role' => UserRole::User]);
        $member->profile()->updateOrCreate([], ['phone' => '081234567891', 'address' => 'Jakarta']);
        $categories = collect(['PlayStation 4', 'PlayStation 5', 'Multiplayer', 'Family', 'Mabar'])->mapWithKeys(fn ($name) => [$name => Category::updateOrCreate(['slug' => str($name)->slug()], ['name' => $name])]);
        foreach ([['PlayStation 4', 'PS4-001', 35000, 4], ['PlayStation 4', 'PS4-002', 35000, 4], ['PlayStation 5', 'PS5-001', 50000, 4], ['PlayStation 5', 'PS5-002', 50000, 4], ['PlayStation 5', 'PS5-003', 50000, 4]] as [$name,$code,$price,$players]) {
            $unit = Unit::updateOrCreate(['code' => $code], compact('name') + ['daily_price' => $price, 'max_players' => $players, 'status' => 'available']);
            $unit->categories()->sync([$categories[$name]->id, $categories['Multiplayer']->id, $categories['Mabar']->id]);
        }
        Combo::updateOrCreate(['slug' => 'bakso-mabar'], ['name' => 'Bakso Mabar', 'duration_days' => 3, 'controller_count' => 4, 'price' => 150000, 'is_active' => true]);
        Combo::updateOrCreate(['slug' => 'bakso-family'], ['name' => 'Bakso Family', 'duration_days' => 2, 'controller_count' => 2, 'price' => 80000, 'is_active' => true]);
    }
}

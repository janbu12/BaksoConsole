<?php

namespace App\Queries;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Delivery;
use App\Models\Fine;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;

class AdminDashboardQuery
{
    public function get(): array
    {
        $popularUnit = Unit::withCount('rentals')->orderByDesc('rentals_count')->first();
        $activeMember = User::where('role', 'user')->withCount('rentals')->orderByDesc('rentals_count')->first();
        $deliveries = Delivery::selectRaw('method, count(*) as total')->groupBy('method')->pluck('total', 'method');

        return [
            'stats' => [
                'Total rental' => Rental::count(),
                'Unit aktif disewa' => Rental::whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])->count(),
                'Unit tersedia' => Unit::where('status', UnitStatus::Available)->count(),
                'Total anggota' => User::where('role', 'user')->count(),
                'Total hari sewa' => Rental::sum('duration_days'),
                'Total transaksi' => Transaction::where('status', PaymentStatus::Paid)->sum('total_amount'),
                'Total denda' => Fine::sum('amount'),
            ],
            'popularUnit' => $popularUnit,
            'activeMember' => $activeMember,
            'deliveryMix' => $deliveries,
            'heatmap' => Rental::selectRaw('start_date, count(*) as total')->groupBy('start_date')->orderBy('start_date')->get(),
        ];
    }
}

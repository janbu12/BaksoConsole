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
        $totalDeliveries = $deliveries->sum();
        $pickupCount = (int) ($deliveries->get('pickup') ?? 0);
        $deliveryCount = (int) ($deliveries->get('delivery') ?? 0);
        
        $pickupPercent = $totalDeliveries > 0 ? round(($pickupCount / $totalDeliveries) * 100) : 50;
        $deliveryPercent = $totalDeliveries > 0 ? round(($deliveryCount / $totalDeliveries) * 100) : 50;

        $heatmapRaw = Rental::selectRaw('start_date, count(*) as total, sum(duration_days) as total_days')
            ->groupBy('start_date')
            ->orderBy('start_date')
            ->get();

        // Identify peak rental period
        $peakDay = $heatmapRaw->sortByDesc('total')->first();

        return [
            'stats' => [
                'Total Rental' => Rental::count(),
                'Unit Aktif Disewa' => Rental::whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])->count(),
                'Unit Tersedia' => Unit::where('status', UnitStatus::Available)->count(),
                'Total Anggota' => User::where('role', 'user')->count(),
                'Total Hari Sewa' => (int) Rental::sum('duration_days'),
                'Total Pendapatan' => (float) Transaction::where('status', PaymentStatus::Paid)->sum('total_amount'),
                'Total Denda' => (float) Fine::sum('amount'),
            ],
            'popularUnit' => $popularUnit,
            'activeMember' => $activeMember,
            'deliveryMix' => [
                'pickup' => $pickupCount,
                'delivery' => $deliveryCount,
                'pickup_percent' => $pickupPercent,
                'delivery_percent' => $deliveryPercent,
            ],
            'heatmap' => $heatmapRaw,
            'peakDay' => $peakDay,
        ];
    }
}

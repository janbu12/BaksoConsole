<?php

namespace App\Domain\Rentals;

use Carbon\CarbonImmutable;

final class RentalWarning
{
    public static function forDueDate(string|CarbonImmutable $dueDate, ?CarbonImmutable $today = null): string
    {
        $due = $dueDate instanceof CarbonImmutable ? $dueDate : CarbonImmutable::parse($dueDate);
        $today ??= CarbonImmutable::today();
        $remaining = (int) $today->startOfDay()->diffInDays($due->startOfDay(), false);

        return $remaining < 0 ? 'overdue' : ($remaining <= 1 ? 'ending_soon' : 'safe');
    }

    public static function details(string|CarbonImmutable $dueDate, ?CarbonImmutable $today = null): array
    {
        $due = $dueDate instanceof CarbonImmutable ? $dueDate : CarbonImmutable::parse($dueDate);
        $today ??= CarbonImmutable::today();
        
        $remaining = (int) $today->startOfDay()->diffInDays($due->startOfDay(), false);

        if ($remaining < 0) {
            $lateDays = abs($remaining);
            return [
                'status' => 'overdue',
                'level' => 'danger',
                'code' => '🔴 Terlambat',
                'badge_class' => 'bg-red-500/20 text-red-400 border border-red-500/30',
                'bg_card' => 'border-red-500/40 bg-red-950/20',
                'icon' => '🔴',
                'title' => 'Masa Sewa Telah Berakhir',
                'message' => "Terlambat {$lateDays} hari dari batas pengembalian (" . $due->format('d M Y') . "). Denda keterlambatan akan dihitung otomatis oleh sistem.",
                'remaining_days' => $remaining,
                'is_safe' => false,
                'is_warning' => false,
                'is_overdue' => true,
            ];
        }

        if ($remaining <= 1) {
            $dayText = $remaining === 0 ? 'Hari ini batas akhir!' : 'Tersisa 1 hari lagi';
            return [
                'status' => 'ending_soon',
                'level' => 'warning',
                'code' => '🟡 Segera Berakhir',
                'badge_class' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
                'bg_card' => 'border-yellow-500/40 bg-yellow-950/20',
                'icon' => '⚠️',
                'title' => 'Masa Sewa Segera Berakhir',
                'message' => "{$dayText} (Jatuh tempo: " . $due->format('d M Y') . "). Harap segera persiapkan pengembalian atau ajukan perpanjangan sewa.",
                'remaining_days' => $remaining,
                'is_safe' => false,
                'is_warning' => true,
                'is_overdue' => false,
            ];
        }

        return [
            'status' => 'safe',
            'level' => 'safe',
            'code' => '🟢 Aman',
            'badge_class' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
            'bg_card' => 'border-emerald-500/30 bg-slate-900',
            'icon' => '🟢',
            'title' => 'Masa Sewa Aktif',
            'message' => "Masa sewa masih tersedia. Sisa: {$remaining} hari (Batas pengembalian: " . $due->format('d M Y') . ").",
            'remaining_days' => $remaining,
            'is_safe' => true,
            'is_warning' => false,
            'is_overdue' => false,
        ];
    }
}

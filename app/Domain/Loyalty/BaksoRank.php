<?php

namespace App\Domain\Loyalty;

final class BaksoRank
{
    public static function fromDays(int $days): array
    {
        if ($days > 30) {
            return [
                'level' => 4,
                'name' => 'Bakso Legend',
                'badge' => '👑 Bakso Legend',
                'color' => 'amber',
                'benefit' => 'Benefit Level 3 — Diskon 15%, Bebas Ongkir & Prioritas VIP',
                'current_days' => $days,
                'min_days' => 31,
                'max_days' => 31,
                'next_rank' => null,
                'days_needed' => 0,
                'progress_percent' => 100,
            ];
        }

        if ($days >= 16) {
            $needed = 31 - $days;
            $progress = round((($days - 16) / (31 - 16)) * 100);

            return [
                'level' => 3,
                'name' => 'Bakso Pro',
                'badge' => '🥇 Bakso Pro',
                'color' => 'yellow',
                'benefit' => 'Benefit Level 2 — Diskon 10% & Prioritas Booking',
                'current_days' => $days,
                'min_days' => 16,
                'max_days' => 30,
                'next_rank' => 'Bakso Legend',
                'days_needed' => $needed,
                'progress_percent' => min(100, max(0, $progress)),
            ];
        }

        if ($days >= 6) {
            $needed = 16 - $days;
            $progress = round((($days - 6) / (16 - 6)) * 100);

            return [
                'level' => 2,
                'name' => 'Bakso Player',
                'badge' => '🥈 Bakso Player',
                'color' => 'slate',
                'benefit' => 'Benefit Level 1 — Diskon 5% untuk Rental Multi-Hari',
                'current_days' => $days,
                'min_days' => 6,
                'max_days' => 15,
                'next_rank' => 'Bakso Pro',
                'days_needed' => $needed,
                'progress_percent' => min(100, max(0, $progress)),
            ];
        }

        $needed = 6 - $days;
        $progress = round(($days / 6) * 100);

        return [
            'level' => 1,
            'name' => 'Bakso Rookie',
            'badge' => '🥉 Bakso Rookie',
            'color' => 'orange',
            'benefit' => 'Member — Akses seluruh rental & penawaran standar',
            'current_days' => $days,
            'min_days' => 0,
            'max_days' => 5,
            'next_rank' => 'Bakso Player',
            'days_needed' => $needed,
            'progress_percent' => min(100, max(0, $progress)),
        ];
    }
}

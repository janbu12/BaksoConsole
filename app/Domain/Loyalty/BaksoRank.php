<?php

namespace App\Domain\Loyalty;

final class BaksoRank
{
    public static function fromDays(int $days): array
    {
        return match (true) {
            $days > 30 => ['name' => 'Bakso Legend', 'benefit' => 'Benefit Level 3'],
            $days >= 16 => ['name' => 'Bakso Pro', 'benefit' => 'Benefit Level 2'],
            $days >= 6 => ['name' => 'Bakso Player', 'benefit' => 'Benefit Level 1'],
            default => ['name' => 'Bakso Rookie', 'benefit' => 'Member'],
        };
    }
}

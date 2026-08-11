<?php

namespace App\Enums;

enum FirmwareType: string
{
    case Original = 'original';
    case Jailbreak = 'jailbreak';

    public function label(): string
    {
        return match($this) {
            self::Original => 'Original / OFW (Online & PSN Ready 🌐)',
            self::Jailbreak => 'Jailbreak / HEN (Full Game Offline 💾)',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::Original => 'Online Ready',
            self::Jailbreak => 'Jailbreak Offline',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Original => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
            self::Jailbreak => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
        };
    }
}

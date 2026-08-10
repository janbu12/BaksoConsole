<?php

namespace App\Enums;

enum FineType: string
{
    case Late = 'late';
    case Damage = 'damage';
    case Other = 'other';
}

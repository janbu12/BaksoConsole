<?php

namespace App\Enums;

enum UnitStatus: string
{
    case Available = 'available';
    case Booked = 'booked';
    case Rented = 'rented';
    case Returned = 'returned';
    case Maintenance = 'maintenance';
}

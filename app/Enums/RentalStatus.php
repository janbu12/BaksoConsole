<?php

namespace App\Enums;

enum RentalStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Overdue = 'overdue';
    case Returned = 'returned';
    case Cancelled = 'cancelled';
}

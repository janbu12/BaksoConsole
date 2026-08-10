<?php

namespace App\Enums;

enum ExtensionStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case ReadyForPickup = 'ready_for_pickup';
    case Waiting = 'waiting';
    case InTransit = 'in_transit';
    case Received = 'received';
    case PickedUp = 'picked_up';
    case ReturnedToOutlet = 'returned_to_outlet';
    case Cancelled = 'cancelled';
}

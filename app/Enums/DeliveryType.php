<?php

namespace App\Enums;

enum DeliveryType: string
{
    case DeliveryOut = 'delivery_out';
    case DeliveryReturn = 'delivery_return';
}

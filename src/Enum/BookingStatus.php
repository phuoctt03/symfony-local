<?php

namespace App\Enum;

enum BookingStatus: string
{
    case PENDING = 'Pending';
    case CONFIRMED = 'Confirmed';
    case CANCELLED = 'Cancelled';
}

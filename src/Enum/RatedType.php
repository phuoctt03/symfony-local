<?php

namespace App\Enum;

enum RatedType: string
{
    case FLIGHT = 'Flight';
    case HOTEL = 'Hotel';
    case ACTIVITY = 'Activity';
    case COMBO = 'Combo';
}

<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case SILVER = 'Silver';
    case GOLD = 'Gold';
}

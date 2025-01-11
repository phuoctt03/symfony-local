<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case CREDIT_CARD = 'Credit Card';
    case DEBIT_CARD = 'Debit Card';
    case PAYPAL = 'PayPal';
    case CASH = 'Cash';
}

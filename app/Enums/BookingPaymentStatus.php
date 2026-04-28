<?php

namespace App\Enums;

enum BookingPaymentStatus: string
{
    case INITIATED = 'Initiated';
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case FAILED = 'Failed';
    case EXPIRED = 'Expired';
    case CANCELED = 'User canceled';
    case REFUNDED = 'Refunded';
}

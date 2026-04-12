<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'Pending';
    case CONFIRMED = 'Confirmed';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}

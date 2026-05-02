<?php

namespace App\Enums;

enum BookingSettlementStatus: string
{
    case HELD = 'Held';
    case RELEASED = 'Released';
}

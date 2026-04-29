<?php

namespace App\Enums;

enum VehicleType: string
{
    case CAR = 'Car';
    case BIKE = 'Bike';
    case SCOOTER = 'Scooter';
    case BUS = 'Bus';
}
<?php

declare(strict_types=1);

namespace App\Application\Enum;

enum ParkingAction: string
{
    case ARRIVAL = 'Arrival';
    case DEPARTURE = 'Departure';
}

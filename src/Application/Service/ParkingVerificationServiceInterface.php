<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\ValueObject\ParkingAvailabilityInterface;

interface ParkingVerificationServiceInterface
{
    public function getAvailability(): ParkingAvailabilityInterface;
}

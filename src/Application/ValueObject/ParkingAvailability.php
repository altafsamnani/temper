<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

class ParkingAvailability implements ParkingAvailabilityInterface
{
    public function __construct(
        protected int $totalSpotsCount,
        protected int $availableSpotsCount,
    ) {
    }

    public function getTotalSpotsCount(): int
    {
        return $this->totalSpotsCount;
    }

    public function getAvailableSpotsCount(): int
    {
        return $this->availableSpotsCount;
    }

    public function hasAvailableSpots(): bool
    {
        return 0 < $this->availableSpotsCount;
    }
}

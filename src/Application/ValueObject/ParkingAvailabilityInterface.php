<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

interface ParkingAvailabilityInterface extends ValueObjectInterface
{
    public function getTotalSpotsCount(): int;

    public function getAvailableSpotsCount(): int;

    public function hasAvailableSpots(): bool;
}

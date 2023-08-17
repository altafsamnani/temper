<?php

declare(strict_types=1);

namespace App\Domain\Entity;

interface VehicleInterface
{
    public function getLicensePlate(): string;
}

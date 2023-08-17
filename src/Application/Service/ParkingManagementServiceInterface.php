<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\ValueObject\ValueObjectInterface;

interface ParkingManagementServiceInterface
{
    public function arrival(ValueObjectInterface $data): void;

    public function departure(ValueObjectInterface $data): void;
}

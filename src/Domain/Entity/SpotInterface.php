<?php

declare(strict_types=1);

namespace App\Domain\Entity;

interface SpotInterface
{
    public function isAvailable(): bool;
}

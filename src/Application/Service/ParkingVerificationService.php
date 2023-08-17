<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\ValueObject\ParkingAvailability;
use App\Domain\Repository\SpotRepository;
use App\Domain\Repository\SpotRepositoryInterface;

 class ParkingVerificationService implements ParkingVerificationServiceInterface
{
    /**
     * @param SpotRepository $spotRepository
     */
    public function __construct(
        protected SpotRepositoryInterface $spotRepository,
    ) {
    }

    public function getAvailability(): ParkingAvailability
    {
        $totalSpotsCount = $this->spotRepository->count([]);
        $availableSpotsCount = $this->spotRepository->countAvailable();

        return new ParkingAvailability($totalSpotsCount, $availableSpotsCount);
    }
}

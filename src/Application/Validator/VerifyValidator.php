<?php

declare(strict_types=1);

namespace App\Application\Validator;

use App\Application\Exception\ValidationException;
use App\Application\Service\ParkingVerificationServiceInterface;
use App\Application\ValueObject\ValidationInterface;

final class VerifyValidator implements ValidatorInterface
{
    public function __construct(
        private ParkingVerificationServiceInterface $parkingLotAvailabilityService,
    ) {
    }

    public function validate(?ValidationInterface $data = null): void
    {
        $availability = $this->parkingLotAvailabilityService->getAvailability();

        if (false === $availability->hasAvailableSpots()) {
            throw new ValidationException('Sorry, no place left.');
        }
    }
}

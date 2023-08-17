<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Exception\ValidationException;
use App\Application\ValueObject\ValidationInterface;
use App\Application\ValueObject\ValueObjectInterface;
use App\Application\ValueObject\VehicleValue;
use App\Domain\Entity\Spot;
use App\Domain\Entity\Vehicle;
use App\Domain\Repository\SpotRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;

class ParkingManagementService implements ParkingManagementServiceInterface
{
    public function __construct(
        protected SpotRepositoryInterface $spotRepository,
        protected VehicleRepositoryInterface $vehicleRepository,
    ) {
    }

    /**
     * @param VehicleValue $data
     */
    public function arrival(ValueObjectInterface $data): void
    {
        $this->checkValidation($data);

        $licensePlate = $data->getLicensePlate();

        /** @var Vehicle|null $vehicle */
        $vehicle = $this->vehicleRepository->findOneBy(['licensePlate' => $licensePlate]);
        $vehicle ??= new Vehicle($licensePlate);

        /** @var Spot $spot */
        $spot = $this->spotRepository->findOneBy(['occupyingVehicle' => null]);

        $vehicle->occupySpot($spot);

        $this->spotRepository->save($spot);
    }

    /**
     * @param VehicleValue $data
     */
    public function departure(ValueObjectInterface $data): void
    {
        $this->checkValidation($data);

        $licensePlate = $data->getLicensePlate();

        /** @var Vehicle $vehicle */
        $vehicle = $this->vehicleRepository->findOneBy(['licensePlate' => $licensePlate]);

        $vehicle->vacateSpot();

        $this->vehicleRepository->save($vehicle);
    }

    protected function checkValidation(ValidationInterface $data): void
    {
        if (false === $data->isValid()) {
            throw new ValidationException('The data provided has not been validated');
        }
    }
}

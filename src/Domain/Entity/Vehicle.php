<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ORM\Table(name: 'vehicles')]
class Vehicle implements VehicleInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    protected string $licensePlate;

    #[ORM\OneToOne(mappedBy: 'occupyingVehicle', targetEntity: Spot::class, cascade: ['all'])]
    protected ?SpotInterface $occupyingSpot = null;

    public function __construct(string $licensePlate)
    {
        $this->licensePlate = $licensePlate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLicensePlate(): string
    {
        return $this->licensePlate;
    }

    public function getSpot(): ?SpotInterface
    {
        return $this->occupyingSpot;
    }

    public function occupySpot(SpotInterface $spot): static
    {
        $this->occupyingSpot = $spot;

        if ($this->occupyingSpot instanceof Spot) {
            $this->occupyingSpot->setOccupyingVehicle($this);
        }

        return $this;
    }

    public function vacateSpot(): static
    {
        if ($this->occupyingSpot instanceof Spot) {
            $this->occupyingSpot->setOccupyingVehicle(null);
        }

        $this->occupyingSpot = null;

        return $this;
    }

    public function isOccupyingSpot(): bool
    {
        return null !== $this->occupyingSpot;
    }
}

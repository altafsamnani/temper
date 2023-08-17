<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\SpotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpotRepository::class)]
#[ORM\Table(name: 'spots')]
class Spot implements SpotInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;

    #[ORM\OneToOne(inversedBy: 'occupyingSpot', targetEntity: Vehicle::class, cascade: ['all'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    protected ?VehicleInterface $occupyingVehicle = null;

    public function isAvailable(): bool
    {
        return null === $this->occupyingVehicle;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVehicle(): ?VehicleInterface
    {
        return $this->occupyingVehicle;
    }

    public function setOccupyingVehicle(?VehicleInterface $vehicle): static
    {
        $this->occupyingVehicle = $vehicle;

        return $this;
    }

    public function hasOccupyingVehicle(): bool
    {
        return null !== $this->occupyingVehicle;
    }
}

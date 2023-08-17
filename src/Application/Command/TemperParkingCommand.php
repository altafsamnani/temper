<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Enum\ParkingAction;
use App\Application\Exception\ValidationException;
use App\Application\Service\ParkingVerificationServiceInterface;
use App\Application\Service\ParkingManagementServiceInterface;
use App\Application\Validator\VerifyValidator;
use App\Application\Validator\ArrivalValidator;
use App\Application\Validator\DepartureValidator;
use App\Application\ValueObject\VehicleValue;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'temper:parking',
    description: 'Run the temper parking app'
)]
class TemperParkingCommand extends Command implements TemperParkingCommandInterface
{
    public function __construct(
        protected ParkingVerificationServiceInterface $parkingLotAvailabilityService,
        protected ParkingManagementServiceInterface   $parkingLotManagerService,
        protected VerifyValidator                     $availabilityValidator,
        protected ArrivalValidator                    $arrivalValidator,
        protected DepartureValidator                  $departureValidator,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->block(messages: "Welcome to Temper Parking Lot, Please go in");
        $action = $io->choice(
            'Are you arriving or departing from parking lot?',
            [ParkingAction::ARRIVAL->value, ParkingAction::DEPARTURE->value]
        );

        try {
            match ($action) {
                ParkingAction::ARRIVAL->value => $this->actionArrival($io),
                ParkingAction::DEPARTURE->value => $this->actionDeparture($io),
                default => throw new \LogicException('An action must be selected'),
            };
        } catch (ValidationException $exception) {
            $io->block(messages: $exception->getMessage(), style: 'fg=white;bg=red', padding: true);
        }
        $keepRunning = $io->confirm('Type yes to continue or no to exit!');
        if ($keepRunning) {
            $this->execute($input, $output);
        }

        return Command::SUCCESS;
    }

    protected function actionArrival(SymfonyStyle $io): void
    {
        $availability = $this->parkingLotAvailabilityService->getAvailability();
        $totalSpotsCount = $availability->getTotalSpotsCount();
        $availableSpotsCount = $availability->getAvailableSpotsCount();
        $this->availabilityValidator->validate();
        $io->block(
            messages: "Available spots: [$availableSpotsCount/$totalSpotsCount]",
            padding: true
        );

        $licensePlate = $io->ask('Enter your license number!');
        $vehicleData = new VehicleValue($licensePlate);
        $this->arrivalValidator->validate($vehicleData);
        $this->parkingLotManagerService->arrival($vehicleData);
        $io->block(messages: 'Please go in!', style: 'fg=black;bg=green', padding: true);
    }

    protected function actionDeparture(SymfonyStyle $io): void
    {
        $licensePlate = $io->ask('What is your license plate?');
        $vehicleData = new VehicleValue($licensePlate);
        $this->departureValidator->validate($vehicleData);
        $this->parkingLotManagerService->departure($vehicleData);
        $io->block(messages: 'Thank you for parking with us!', style: 'fg=black;bg=green', padding: true);
    }
}

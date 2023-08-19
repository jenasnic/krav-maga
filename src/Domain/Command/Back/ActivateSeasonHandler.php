<?php

namespace App\Domain\Command\Back;

use App\Repository\SeasonRepository;
use App\Service\Notifier\ReEnrollmentNotifier;
use Doctrine\ORM\EntityManagerInterface;

final class ActivateSeasonHandler
{
    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ReEnrollmentNotifier $reEnrollmentNotifier,
    ) {
    }

    public function handle(ActivateSeasonCommand $command): void
    {
        $previousSeason = $this->seasonRepository->getActiveSeason();
        if (null !== $previousSeason) {
            $previousSeason->setActive(false);
        }

        $command->season->setActive(true);
        $this->entityManager->flush();

        if (null !== $previousSeason) {
            $this->reEnrollmentNotifier->notify($previousSeason);
        }
    }
}

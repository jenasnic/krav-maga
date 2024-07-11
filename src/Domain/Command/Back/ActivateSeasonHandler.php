<?php

namespace App\Domain\Command\Back;

use App\Repository\AdherentRepository;
use App\Repository\ReEnrollmentTokenRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ActivateSeasonHandler
{
    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly AdherentRepository $adherentRepository,
        private readonly ReEnrollmentTokenRepository $reEnrollmentTokenRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ActivateSeasonCommand $command): void
    {
        $this->entityManager->beginTransaction();

        try {
            $previousSeason = $this->seasonRepository->getActiveSeason();
            if (null !== $previousSeason) {
                $previousSeason->setActive(false);

                /** @var int $previousSeasonId */
                $previousSeasonId = $previousSeason->getId();
                $this->adherentRepository->setReEnrollmentToNotify($previousSeasonId);
            }

            // NOTE: remove all tokens since we activate new season => require to generate new re-enrollment tokens!
            $this->reEnrollmentTokenRepository->removeAllToken();
            $command->season->setActive(true);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable) {
            $this->entityManager->rollback();
        }
    }
}

<?php

namespace App\Domain\Command\Back;

use App\Repository\AdherentRepository;
use App\Repository\SeasonRepository;
use App\Service\Email\EmailSender;
use App\Service\Factory\ReEnrollmentTokenFactory;
use Doctrine\ORM\EntityManagerInterface;

final class ActivateSeasonHandler
{
    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly AdherentRepository $adherentRepository,
        private readonly ReEnrollmentTokenFactory $reEnrollmentTokenFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailSender $emailSender,
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
            /** @var int $seasonId */
            $seasonId = $previousSeason->getId();
            $adherents = $this->adherentRepository->findForSeason($seasonId);

            foreach ($adherents as $adherent) {
                $token = $this->reEnrollmentTokenFactory->create($adherent);
                $this->entityManager->persist($token);
                $this->entityManager->flush();

                /** @var string $adherentEmail */
                $adherentEmail = $adherent->getEmail();

                $this->emailSender->send(
                    'email/re_enrollment.html.twig',
                    $adherentEmail,
                    [
                        'adherent' => $adherent,
                        'token' => $token,
                        'season' => $command->season,
                    ]
                );
            }
        }
    }
}

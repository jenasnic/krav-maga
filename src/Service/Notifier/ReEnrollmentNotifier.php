<?php

namespace App\Service\Notifier;

use App\Entity\Season;
use App\Repository\AdherentRepository;
use App\Repository\SeasonRepository;
use App\Service\Email\EmailSender;
use App\Service\Factory\ReEnrollmentTokenFactory;
use Doctrine\ORM\EntityManagerInterface;

class ReEnrollmentNotifier
{
    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly AdherentRepository $adherentRepository,
        private readonly ReEnrollmentTokenFactory $reEnrollmentTokenFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailSender $emailSender,
    ) {
    }

    /**
     * @param Season $season season used to identify adherents that need to receive re-enrollment email
     * @param ?int $limit limit on sent emails to avoid "Mails peer session limit" error
     *
     * @return int Number of re-enrollment emails sent
     */
    public function notify(Season $season, ?int $limit = null): int
    {
        $activeSeason = $this->seasonRepository->getActiveSeason();

        if (null === $activeSeason || $activeSeason->getId() === $season->getId()) {
            throw new \LogicException('can not process re-enrollment notification (specified season already active or no active season)');
        }

        /** @var int $seasonId */
        $seasonId = $season->getId();
        $adherents = $this->adherentRepository->findForReEnrollment($seasonId);

        $processedAdherentCount = 0;
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
                    'season' => $activeSeason,
                ]
            );

            ++$processedAdherentCount;

            if (null !== $limit && $processedAdherentCount >= $limit) {
                break;
            }
        }

        return $processedAdherentCount;
    }
}

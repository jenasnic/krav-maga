<?php

namespace App\Service\Notifier;

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
     * @param int $limit limit on sent emails to avoid "Mails peer session limit" error (0 means no limit)
     *
     * @return int Number of re-enrollment emails sent
     */
    public function notify(int $limit = 0): int
    {
        $season = $this->seasonRepository->getActiveSeason();

        if (null === $season) {
            return 0;
        }

        $adherents = $this->adherentRepository->findWithReEnrollmentToNotify($limit);

        $emailSentCount = 0;
        foreach ($adherents as $adherent) {
            $token = $this->reEnrollmentTokenFactory->create($adherent, $season);
            $adherent->setReEnrollmentToNotify(false);
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
                    'season' => $season,
                ]
            );

            ++$emailSentCount;
        }

        return $emailSentCount;
    }
}

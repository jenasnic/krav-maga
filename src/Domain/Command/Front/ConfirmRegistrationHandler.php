<?php

namespace App\Domain\Command\Front;

use App\Enum\PassSportEnum;
use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class ConfirmRegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailBuilder $emailBuilder,
        private readonly EmailSender $emailSender,
    ) {
    }

    public function handle(ConfirmRegistrationCommand $command): void
    {
        $registration = $command->registration;

        if (null === $registration->getId() || null === $registration->getAdherent()->getEmail()) {
            throw new LogicException('invalid registration');
        }

        $this->verifyEmailHelper->validateEmailConfirmation(
            $command->request->getUri(),
            (string) $registration->getId(),
            $registration->getAdherent()->getEmail()
        );

        $registration->setVerified(true);

        if (null === $command->registration->getAdherent()->getEmail()) {
            throw new LogicException('invalid registration');
        }

        $discountCode = match (true) {
            $registration->isUsePass15() && $registration->isUsePass50() => PassSportEnum::BOTH,
            $registration->isUsePass15() => PassSportEnum::PASS_15,
            $registration->isUsePass50() => PassSportEnum::PASS_50,
            default => null,
        };

        $this->entityManager->flush();

        /** @var string $adherentEmail */
        $adherentEmail = $registration->getAdherent()->getEmail();

        $email = $this->emailBuilder
            ->useTemplate('email/registration_confirmed.html.twig', [
                'registration' => $command->registration,
                'discountCode' => $discountCode,
            ])
            ->fromDefault()
            ->to($adherentEmail)
            ->copy()
            ->getEmail()
        ;

        $this->emailSender->sendEmail($email);
    }
}

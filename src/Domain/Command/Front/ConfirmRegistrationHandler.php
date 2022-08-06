<?php

namespace App\Domain\Command\Front;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class ConfirmRegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly EntityManagerInterface $entityManager,
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

        $this->entityManager->flush();
    }
}

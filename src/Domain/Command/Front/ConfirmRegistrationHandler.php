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
        $adherent = $command->adherent;

        if (null === $adherent->getId() || null === $adherent->getEmail()) {
            throw new LogicException('invalid adherent');
        }

        $this->verifyEmailHelper->validateEmailConfirmation($command->request->getUri(), (string) $adherent->getId(), $adherent->getEmail());

        $adherent->setVerified(true);

        $this->entityManager->flush();
    }
}

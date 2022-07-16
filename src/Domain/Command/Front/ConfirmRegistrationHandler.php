<?php

namespace App\Domain\Command\Front;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class ConfirmRegistrationHandler
{
    public function __construct(
        protected VerifyEmailHelperInterface $verifyEmailHelper,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ConfirmRegistrationCommand $command): void
    {
        $member = $command->member;

        if (null === $member->getId() || null === $member->getEmail()) {
            throw new LogicException('invalid member');
        }

        $this->verifyEmailHelper->validateEmailConfirmation($command->request->getUri(), (string) $member->getId(), $member->getEmail());

        $member->setVerified(true);

        $this->entityManager->flush();
    }
}

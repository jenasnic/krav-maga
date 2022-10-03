<?php

namespace App\Domain\Command\Back;

use Doctrine\ORM\EntityManagerInterface;

final class EditPaymentHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(EditPaymentCommand $command): void
    {
        $this->entityManager->flush();
    }
}

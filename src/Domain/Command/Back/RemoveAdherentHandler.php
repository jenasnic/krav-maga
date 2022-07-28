<?php

namespace App\Domain\Command\Back;

use App\Repository\AdherentRepository;

final class RemoveAdherentHandler
{
    public function __construct(
        private readonly AdherentRepository $adherentRepository,
    ) {
    }

    public function handle(RemoveAdherentCommand $command): void
    {
        $fileToRemove = $command->adherent->getRegistrationInfo()?->getMedicalCertificateUrl();

        $this->adherentRepository->remove($command->adherent, true);

        if (!empty($fileToRemove) && file_exists($fileToRemove)) {
            unlink($fileToRemove);
        }
    }
}

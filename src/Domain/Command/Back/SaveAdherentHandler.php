<?php

namespace App\Domain\Command\Back;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use App\Service\FileUploader;
use LogicException;

final class SaveAdherentHandler
{
    public function __construct(
        private readonly AdherentRepository $adherentRepository,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function handle(SaveAdherentCommand $command): void
    {
        $this->processFile($command->adherent);

        $this->adherentRepository->add($command->adherent, true);
    }

    private function processFile(Adherent $adherent): void
    {
        if (null !== $adherent->getPictureFile()) {
            if (null !== $adherent->getPictureUrl() && file_exists($adherent->getPictureUrl())) {
                unlink($adherent->getPictureUrl());
            }

            $adherent->setPictureUrl($this->fileUploader->upload($adherent->getPictureFile()));
        }

        if (null === $adherent->getRegistrationInfo()) {
            throw new LogicException('invalid registration info');
        }

        $medicalCertificateFile = $adherent->getRegistrationInfo()->getMedicalCertificateFile();
        if (null !== $medicalCertificateFile) {
            if (
                null !== $adherent->getRegistrationInfo()->getMedicalCertificateUrl()
                && file_exists($adherent->getRegistrationInfo()->getMedicalCertificateUrl())
            ) {
                unlink($adherent->getRegistrationInfo()->getMedicalCertificateUrl());
            }

            $adherent->getRegistrationInfo()->setMedicalCertificateUrl($this->fileUploader->upload($medicalCertificateFile));
        }
    }
}

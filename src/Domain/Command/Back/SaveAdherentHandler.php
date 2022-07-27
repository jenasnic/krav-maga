<?php

namespace App\Domain\Command\Back;

use App\Entity\RegistrationInfo;
use App\Repository\AdherentRepository;
use LogicException;

final class SaveAdherentHandler
{
    public function __construct(
        private readonly AdherentRepository $adherentRepository,
        private readonly string $uploadPath,
    ) {
    }

    public function handle(SaveAdherentCommand $command): void
    {
        if (null === $command->adherent->getRegistrationInfo()) {
            throw new LogicException('invalid registration info');
        }

        $this->processFile($command->adherent->getRegistrationInfo());

        $this->adherentRepository->add($command->adherent, true);
    }

    private function processFile(RegistrationInfo $registrationInfo): void
    {
        if (null !== $registrationInfo->getMedicalCertificateFile()) {
            if (null !== $registrationInfo->getMedicalCertificateUrl() && file_exists($registrationInfo->getMedicalCertificateUrl())) {
                unlink($registrationInfo->getMedicalCertificateUrl());
            }

            $fileName = sprintf(
                '%s.%s',
                str_replace('.', '', uniqid('', true)),
                $registrationInfo->getMedicalCertificateFile()->getClientOriginalExtension()
            );

            $registrationInfo->getMedicalCertificateFile()->move($this->uploadPath, $fileName);
            $registrationInfo->setMedicalCertificateUrl($this->uploadPath.DIRECTORY_SEPARATOR.$fileName);
        }
    }
}

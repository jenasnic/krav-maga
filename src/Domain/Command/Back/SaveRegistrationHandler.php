<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Service\FileUploader;

final class SaveRegistrationHandler
{
    public function __construct(
        private readonly RegistrationRepository $registrationRepository,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function handle(SaveRegistrationCommand $command): void
    {
        $this->processFile($command->registration);

        $this->registrationRepository->add($command->registration, true);
    }

    private function processFile(Registration $registration): void
    {
        if (null !== $registration->getMedicalCertificateFile()) {
            if (
                null !== $registration->getMedicalCertificateUrl()
                && file_exists($registration->getMedicalCertificateUrl())
            ) {
                unlink($registration->getMedicalCertificateUrl());
            }

            $registration->setMedicalCertificateUrl($this->fileUploader->upload($registration->getMedicalCertificateFile()));
        }

        if (null !== $registration->getLicenceFormFile()) {
            if (
                null !== $registration->getLicenceFormUrl()
                && file_exists($registration->getLicenceFormUrl())
            ) {
                unlink($registration->getLicenceFormUrl());
            }

            $registration->setLicenceFormUrl($this->fileUploader->upload($registration->getLicenceFormFile()));
        }

        // @todo : replace pass15 and pass50 files
    }
}

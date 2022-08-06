<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Service\FileUploader;

final class NewRegistrationHandler
{
    public function __construct(
        private readonly RegistrationRepository $registrationRepository,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function handle(NewRegistrationCommand $command): void
    {
        $registration = $command->registration;

        $this->processUpload($registration);

        $this->registrationRepository->add($registration, true);
    }

    private function processUpload(Registration $registration): void
    {
        if (null !== $registration->getAdherent()->getPictureFile()) {
            $registration->getAdherent()->setPictureUrl($this->fileUploader->upload($registration->getAdherent()->getPictureFile()));
        }

        if (null !== $registration->getMedicalCertificateFile()) {
            $registration->setMedicalCertificateUrl($this->fileUploader->upload($registration->getMedicalCertificateFile()));
        }
    }
}

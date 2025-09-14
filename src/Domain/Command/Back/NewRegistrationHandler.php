<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Service\File\FileUploader;

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

        if (null !== $registration->getLicenceFormFile()) {
            $registration->setLicenceFormUrl($this->fileUploader->upload($registration->getLicenceFormFile()));
        }

        // @todo : check if usePassCitizen is true?
        if (null !== $registration->getPassCitizenFile()) {
            $registration->setPassCitizenUrl($this->fileUploader->upload($registration->getPassCitizenFile()));
        }

        // @todo : check if usePassSport is true?
        if (null !== $registration->getPassSportFile()) {
            $registration->setPassSportUrl($this->fileUploader->upload($registration->getPassSportFile()));
        }
    }
}

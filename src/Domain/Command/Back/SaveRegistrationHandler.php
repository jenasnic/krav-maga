<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;
use App\Enum\FileTypeEnum;
use App\Repository\RegistrationRepository;
use App\Service\File\FileCleaner;
use App\Service\File\FileUploader;

final class SaveRegistrationHandler
{
    public function __construct(
        private readonly RegistrationRepository $registrationRepository,
        private readonly FileUploader $fileUploader,
        private readonly FileCleaner $fileCleaner,
    ) {
    }

    public function handle(SaveRegistrationCommand $command): void
    {
        $this->processFile($command->registration);

        $this->registrationRepository->add($command->registration, true);
    }

    private function processFile(Registration $registration): void
    {
        $medicalCertificateFile = $registration->getMedicalCertificateFile();
        if (null !== $medicalCertificateFile) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::MEDICAL_CERTIFICATE);
            $registration->setMedicalCertificateUrl($this->fileUploader->upload($medicalCertificateFile));
        }

        $licenceFormFile = $registration->getLicenceFormFile();
        if (null !== $licenceFormFile) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::LICENCE_FORM);
            $registration->setLicenceFormUrl($this->fileUploader->upload($licenceFormFile));
        }

        $pass15File = $registration->getPass15File();
        if (null !== $pass15File) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_15);
            $registration->setPass15Url($this->fileUploader->upload($pass15File));
        }

        $pass50File = $registration->getPass50File();
        if (null !== $pass50File) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_50);
            $registration->setPass50Url($this->fileUploader->upload($pass50File));
        }
    }
}

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

        $passCitizenFile = $registration->getPassCitizenFile();
        if (null !== $passCitizenFile) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_CITIZEN);
            $registration->setPassCitizenUrl($this->fileUploader->upload($passCitizenFile));
        }

        $passSportFile = $registration->getPassSportFile();
        if (null !== $passSportFile) {
            $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_SPORT);
            $registration->setPassSportUrl($this->fileUploader->upload($passSportFile));
        }
    }
}

<?php

namespace App\Domain\Command\Front;

use App\Entity\Registration;
use App\Enum\FileTypeEnum;
use App\Enum\PassSportEnum;
use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use App\Service\File\FileCleaner;
use App\Service\File\FileUploader;
use Doctrine\ORM\EntityManagerInterface;

final class ReEnrollmentHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailBuilder $emailBuilder,
        private readonly EmailSender $emailSender,
        private readonly FileUploader $fileUploader,
        private readonly FileCleaner $fileCleaner,
    ) {
    }

    public function handle(ReEnrollmentCommand $command): void
    {
        $registration = $command->registration;

        $this->processUpload($registration);
        $this->entityManager->remove($command->reEnrollmentToken);
        $this->entityManager->flush();

        $discountCode = match (true) {
            $registration->isUsePass15() && $registration->isUsePass50() => PassSportEnum::BOTH,
            $registration->isUsePass15() => PassSportEnum::PASS_15,
            $registration->isUsePass50() => PassSportEnum::PASS_50,
            default => null,
        };

        /** @var string $adherentEmail */
        $adherentEmail = $registration->getAdherent()->getEmail();

        $email = $this->emailBuilder
            ->useTemplate('email/re_enrollment_confirmed.html.twig', [
                'registration' => $registration,
                'discountCode' => $discountCode,
            ])
            ->fromDefault()
            ->to($adherentEmail)
            ->copy()
            ->getEmail()
        ;

        $this->emailSender->sendEmail($email);
    }

    private function processUpload(Registration $registration): void
    {
        $pictureFile = $registration->getAdherent()->getPictureFile();
        if (null !== $pictureFile) {
            $this->fileCleaner->cleanEntity($registration->getAdherent(), FileTypeEnum::PICTURE);
            $registration->getAdherent()->setPictureUrl($this->fileUploader->upload($pictureFile));
        }

        if (null !== $registration->getMedicalCertificateFile()) {
            $registration->setMedicalCertificateUrl($this->fileUploader->upload($registration->getMedicalCertificateFile()));
        }

        if (null !== $registration->getLicenceFormFile()) {
            $registration->setLicenceFormUrl($this->fileUploader->upload($registration->getLicenceFormFile()));
        }

        // @todo : check if usePass15 is true?
        if (null !== $registration->getPass15File()) {
            $registration->setPass15Url($this->fileUploader->upload($registration->getPass15File()));
        }

        // @todo : check if usePass50 is true?
        if (null !== $registration->getPass50File()) {
            $registration->setPass50Url($this->fileUploader->upload($registration->getPass50File()));
        }
    }
}

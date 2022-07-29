<?php

namespace App\Domain\Command\Front;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use App\Service\Email\EmailSender;
use App\Service\FileUploader;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly AdherentRepository $adherentRepository,
        private readonly EmailSender $emailSender,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function handle(RegistrationCommand $command): void
    {
        $adherent = $command->adherent;

        if (null !== $adherent->getId()) {
            throw new LogicException('adherent already persisted');
        }

        $this->processUpload($adherent);

        $this->adherentRepository->add($adherent, true);

        $this->sendConfirmationEmail($adherent);
    }

    private function processUpload(Adherent $adherent): void
    {
        if (null !== $adherent->getPictureFile()) {
            $adherent->setPictureUrl($this->fileUploader->upload($adherent->getPictureFile()));
        }

        if (null === $adherent->getRegistrationInfo()) {
            throw new LogicException('invalid registration info');
        }

        $medicalCertificateFile = $adherent->getRegistrationInfo()->getMedicalCertificateFile();
        if (null !== $medicalCertificateFile) {
            $adherent->getRegistrationInfo()->setMedicalCertificateUrl($this->fileUploader->upload($medicalCertificateFile));
        }
    }

    private function sendConfirmationEmail(Adherent $adherent): void
    {
        if (null === $adherent->getId() || null === $adherent->getEmail()) {
            throw new LogicException('invalid adherent');
        }

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_confirm_registration',
            (string) $adherent->getId(),
            $adherent->getEmail(),
            [
                'adherent' => $adherent->getId(),
            ]
        );

        $this->emailSender->send(
            'email/registration.html.twig',
            [
                $adherent->getEmail(),
            ],
            [
                'adherent' => $adherent,
                'confirmLink' => $signatureComponents->getSignedUrl(),
            ],
        );
    }
}

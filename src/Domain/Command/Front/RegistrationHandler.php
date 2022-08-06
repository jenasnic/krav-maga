<?php

namespace App\Domain\Command\Front;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Service\Email\EmailSender;
use App\Service\FileUploader;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly RegistrationRepository $registrationRepository,
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly EmailSender $emailSender,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function handle(RegistrationCommand $command): void
    {
        $registration = $command->registration;

        if (null !== $registration->getId()) {
            throw new LogicException('registration already persisted');
        }

        $this->processUpload($registration);

        $this->registrationRepository->add($registration, true);

        $this->sendConfirmationEmail($registration);
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

    private function sendConfirmationEmail(Registration $registration): void
    {
        if (null === $registration->getId() || null === $registration->getAdherent()->getEmail()) {
            throw new LogicException('invalid adherent');
        }

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_confirm_registration',
            (string) $registration->getId(),
            $registration->getAdherent()->getEmail(),
            [
                'registration' => $registration->getId(),
            ]
        );

        $this->emailSender->send(
            'email/registration.html.twig',
            [
                $registration->getAdherent()->getEmail(),
            ],
            [
                'registration' => $registration,
                'confirmLink' => $signatureComponents->getSignedUrl(),
            ],
        );
    }
}

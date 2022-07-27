<?php

namespace App\Domain\Command\Front;

use App\Entity\Adherent;
use App\Entity\RegistrationInfo;
use App\Repository\AdherentRepository;
use App\Service\Email\EmailSender;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly AdherentRepository $adherentRepository,
        private readonly EmailSender $emailSender,
        private readonly string $uploadPath,
    ) {
    }

    public function handle(RegistrationCommand $command): void
    {
        $adherent = $command->adherent;

        if (null !== $adherent->getId()) {
            throw new LogicException('adherent already persisted');
        }

        if (null === $adherent->getRegistrationInfo()) {
            throw new LogicException('invalid registration info');
        }

        $this->processUpload($adherent->getRegistrationInfo());

        $this->adherentRepository->add($adherent, true);

        $this->sendConfirmationEmail($adherent);
    }

    private function processUpload(RegistrationInfo $registrationInfo): void
    {
        if (null !== $registrationInfo->getMedicalCertificateFile()) {
            $fileName = sprintf(
                '%s.%s',
                str_replace('.', '', uniqid('', true)),
                $registrationInfo->getMedicalCertificateFile()->getClientOriginalExtension()
            );

            $registrationInfo->getMedicalCertificateFile()->move($this->uploadPath, $fileName);
            $registrationInfo->setMedicalCertificateUrl($this->uploadPath.DIRECTORY_SEPARATOR.$fileName);
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

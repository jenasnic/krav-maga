<?php

namespace App\Domain\Command\Front;

use App\Entity\Member;
use App\Entity\RegistrationInfo;
use App\Repository\MemberRepository;
use App\Service\Email\EmailSender;
use LogicException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly MemberRepository $memberRepository,
        private readonly EmailSender $emailSender,
        private readonly string $uploadPath,
    ) {
    }

    public function handle(RegistrationCommand $command): void
    {
        $member = $command->member;

        if (null !== $member->getId()) {
            throw new LogicException('member already persisted');
        }

        $this->processUpload($member->getRegistrationInfo());

        $this->memberRepository->add($member, true);

        $this->sendConfirmationEmail($member);
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

    private function sendConfirmationEmail(Member $member): void
    {
        if (null === $member->getId() || null === $member->getEmail()) {
            throw new LogicException('invalid member');
        }

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_confirm_registration',
            (string) $member->getId(),
            $member->getEmail(),
            [
                'member' => $member->getId(),
            ]
        );

        $this->emailSender->send(
            'email/registration.html.twig',
            [
                $member->getEmail(),
            ],
            [
                'member' => $member,
                'confirmLink' => $signatureComponents->getSignedUrl(),
            ],
        );
    }
}

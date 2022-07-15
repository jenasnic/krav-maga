<?php

namespace App\Domain\Command;

use App\Entity\RegistrationInfo;
use App\Repository\MemberRepository;
use LogicException;

final class RegistrationHandler
{
    public function __construct(
        private MemberRepository $memberRepository,
        private string $uploadPath,
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
}

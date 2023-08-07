<?php

namespace App\Domain\Command\Front;

use App\Entity\Payment\PriceOption;
use App\Entity\Registration;
use App\Entity\Season;
use App\Enum\DiscountCodeEnum;
use App\Enum\FileTypeEnum;
use App\Repository\ReEnrollmentTokenRepository;
use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use App\Service\File\FileCleaner;
use App\Service\File\FileUploader;
use Doctrine\ORM\EntityManagerInterface;

final class ReEnrollmentHandler
{
    use RegistrationTrait;

    public function __construct(
        private readonly ReEnrollmentTokenRepository $reEnrollmentTokenRepository,
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
        $this->reEnrollmentTokenRepository->remove($command->reEnrollmentToken, true);

        /** @var string $adherentEmail */
        $adherentEmail = $registration->getAdherent()->getEmail();
        $discountCode = $this->getDiscountCode($registration);
        $amountToPay = $this->getAmountToPay($registration);

        $reEnrollmentCode = $registration->getPriceOption()->getId() === $this->getMostExpensivePriceOption($registration->getSeason())->getId()
            ? DiscountCodeEnum::KMIS_30
            : DiscountCodeEnum::KMIS_20
        ;

        $amountToPay -= (DiscountCodeEnum::KMIS_30 === $reEnrollmentCode) ? 30 : 20;

        $email = $this->emailBuilder
            ->useTemplate('email/re_enrollment_confirmed.html.twig', [
                'registration' => $registration,
                'reEnrollmentCode' => $reEnrollmentCode,
                'discountCode' => $discountCode,
                'amountToPay' => $amountToPay,
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

        // @todo : check if usePassCitizen is true?
        if (null !== $registration->getPassCitizenFile()) {
            $registration->setPassCitizenUrl($this->fileUploader->upload($registration->getPassCitizenFile()));
        }

        // @todo : check if usePassSport is true?
        if (null !== $registration->getPassSportFile()) {
            $registration->setPassSportUrl($this->fileUploader->upload($registration->getPassSportFile()));
        }
    }

    private function getMostExpensivePriceOption(Season $season): PriceOption
    {
        /** @var PriceOption $result */
        $result = $season->getPriceOptions()->first();

        foreach ($season->getPriceOptions() as $priceOption) {
            if ($priceOption->getAmount() > $result->getAmount()) {
                $result = $priceOption;
            }
        }

        return $result;
    }
}

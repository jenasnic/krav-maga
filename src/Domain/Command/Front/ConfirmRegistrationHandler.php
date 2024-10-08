<?php

namespace App\Domain\Command\Front;

use App\Enum\DiscountCodeEnum;
use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class ConfirmRegistrationHandler
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailBuilder $emailBuilder,
        private readonly EmailSender $emailSender,
    ) {
    }

    public function handle(ConfirmRegistrationCommand $command): void
    {
        $registration = $command->registration;

        if (null === $registration->getId() || null === $registration->getAdherent()->getEmail()) {
            throw new \LogicException('invalid registration');
        }

        $this->verifyEmailHelper->validateEmailConfirmation(
            $command->request->getUri(),
            (string) $registration->getId(),
            $registration->getAdherent()->getEmail()
        );

        $registration->setVerified(true);

        if (null === $command->registration->getAdherent()->getEmail()) {
            throw new \LogicException('invalid registration');
        }

        $this->entityManager->flush();

        /** @var string $adherentEmail */
        $adherentEmail = $registration->getAdherent()->getEmail();
        $discountCode = DiscountCodeEnum::getDiscountCode($registration);

        /** @var float $amountToPay */
        $amountToPay = $registration->getPriceOption()?->getAmount();
        if (null !== $discountCode) {
            $amountToPay -= DiscountCodeEnum::getDiscountAmount($discountCode);
        }

        $email = $this->emailBuilder
            ->useTemplate('email/registration_confirmed.html.twig', [
                'registration' => $command->registration,
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
}

<?php

namespace App\Domain\Command\Front;

use App\Entity\Registration;
use App\Enum\PassSportEnum;

trait RegistrationTrait
{
    protected function getDiscountCode(Registration $registration): ?string
    {
        return match (true) {
            $registration->isUsePass15() && $registration->isUsePass50() => PassSportEnum::BOTH,
            $registration->isUsePass15() => PassSportEnum::PASS_15,
            $registration->isUsePass50() => PassSportEnum::PASS_50,
            default => null,
        };
    }

    protected function getAmountToPay(Registration $registration): float
    {
        /** @var float $amountToPay */
        $amountToPay = $registration->getPriceOption()?->getAmount();

        if ($registration->isUsePass15()) {
            $amountToPay -= 15;
        }

        if ($registration->isUsePass15()) {
            $amountToPay -= 50;
        }

        return $amountToPay;
    }
}

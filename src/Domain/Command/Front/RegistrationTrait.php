<?php

namespace App\Domain\Command\Front;

use App\Entity\Registration;
use App\Enum\DiscountCodeEnum;

trait RegistrationTrait
{
    protected function getDiscountCode(Registration $registration): ?string
    {
        return match (true) {
            $registration->isUsePassCitizen() && $registration->isUsePassSport() => DiscountCodeEnum::BOTH,
            $registration->isUsePassCitizen() => DiscountCodeEnum::PASS_20,
            $registration->isUsePassSport() => DiscountCodeEnum::PASS_50,
            default => null,
        };
    }

    protected function getAmountToPay(Registration $registration): float
    {
        /** @var float $amountToPay */
        $amountToPay = $registration->getPriceOption()?->getAmount();

        if ($registration->isUsePassCitizen()) {
            $amountToPay -= 20;
        }

        if ($registration->isUsePassCitizen()) {
            $amountToPay -= 50;
        }

        return $amountToPay;
    }
}

<?php

namespace App\Enum;

use App\Entity\Registration;

class DiscountCodeEnum
{
    public const PASS_CITIZEN = 'PASS15';
    public const PASS_SPORT = 'PASS50';
    public const BOTH = 'PASS50+15';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::PASS_CITIZEN,
            self::PASS_SPORT,
            self::BOTH,
        ];
    }

    public static function getDiscountCode(Registration $registration): ?string
    {
        return match (true) {
            $registration->isUsePassCitizen() && $registration->isUsePassSport() => self::BOTH,
            $registration->isUsePassCitizen() => self::PASS_CITIZEN,
            $registration->isUsePassSport() => self::PASS_SPORT,
            default => null,
        };
    }

    public static function getDiscountAmount(string $discountCode): float
    {
        return match ($discountCode) {
            self::PASS_CITIZEN => 15,
            self::PASS_SPORT => 50,
            self::BOTH => 65,
            default => 0,
        };
    }
}

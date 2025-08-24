<?php

namespace App\Enum;

use App\Entity\Registration;

class DiscountCodeEnum
{
    public const PASS_CITIZEN = '15';
    public const PASS_SPORT = '70';
    public const CCAS = '10';
    public const CITIZEN_AND_SPORT = '70+15';
    public const CITIZEN_AND_CCAS = '15+10';
    public const ALL = '70+15+10';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::PASS_CITIZEN,
            self::PASS_SPORT,
            self::CCAS,
            self::CITIZEN_AND_SPORT,
            self::CITIZEN_AND_CCAS,
            self::ALL,
        ];
    }

    public static function getDiscountCode(Registration $registration): ?string
    {
        return match (true) {
            $registration->isUsePassCitizen() && $registration->isUsePassSport() && $registration->isUseCCAS() => self::ALL,
            $registration->isUsePassCitizen() && $registration->isUsePassSport() => self::CITIZEN_AND_SPORT,
            $registration->isUsePassCitizen() && $registration->isUseCCAS() => self::CITIZEN_AND_CCAS,
            $registration->isUsePassCitizen() => self::PASS_CITIZEN,
            $registration->isUsePassSport() => self::PASS_SPORT,
            $registration->isUseCCAS() => self::CCAS,
            default => null,
        };
    }

    public static function getDiscountAmount(string $discountCode): float
    {
        return match ($discountCode) {
            self::PASS_CITIZEN => 15,
            self::PASS_SPORT => 70,
            self::CCAS => 10,
            self::CITIZEN_AND_SPORT => 85,
            self::CITIZEN_AND_CCAS => 25,
            self::ALL => 95,
            default => 0,
        };
    }
}

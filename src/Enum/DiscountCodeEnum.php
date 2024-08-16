<?php

namespace App\Enum;

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
}

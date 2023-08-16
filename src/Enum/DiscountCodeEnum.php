<?php

namespace App\Enum;

class DiscountCodeEnum
{
    public const PASS_20 = 'PASS20';
    public const PASS_50 = 'PASS50';
    public const BOTH = 'PASS50+20';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::PASS_20,
            self::PASS_50,
            self::BOTH,
        ];
    }
}

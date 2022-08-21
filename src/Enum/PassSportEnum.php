<?php

namespace App\Enum;

class PassSportEnum
{
    public const PASS_15 = 'PASS15';
    public const PASS_50 = 'PASS50';
    public const BOTH = 'PASS50+15';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::PASS_15,
            self::PASS_50,
            self::BOTH,
        ];
    }
}

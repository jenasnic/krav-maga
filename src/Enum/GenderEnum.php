<?php

namespace App\Enum;

class GenderEnum
{
    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::MALE,
            self::FEMALE,
        ];
    }
}

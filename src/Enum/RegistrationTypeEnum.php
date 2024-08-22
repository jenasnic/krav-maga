<?php

namespace App\Enum;

class RegistrationTypeEnum
{
    public const ADULT = 'ADULT';
    public const COMPETITOR = 'COMPETITOR';
    public const MINOR = 'MINOR';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::ADULT,
            self::COMPETITOR,
            self::MINOR,
        ];
    }
}

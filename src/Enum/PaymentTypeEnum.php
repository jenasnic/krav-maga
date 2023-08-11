<?php

namespace App\Enum;

class PaymentTypeEnum
{
    public const ANCV = 'ANCV';
    public const CASH = 'CASH';
    public const CHECK = 'CHECK';
    public const DISCOUNT = 'DISCOUNT';
    public const HELLO_ASSO = 'HELLO_ASSO';
    public const PASS = 'PASS';
    public const TRANSFER = 'TRANSFER';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::ANCV,
            self::CASH,
            self::CHECK,
            self::DISCOUNT,
            self::HELLO_ASSO,
            self::PASS,
            self::TRANSFER,
        ];
    }
}

<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\CheckPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CheckPayment>
 */
final class CheckPaymentFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomElement([240, 120, 80, 60]),
            'date' => self::faker()->dateTimeBetween('-3 months', '-1 week'),
            'number' => self::faker()->numberBetween(100000, 999999),
            'cashingDate' => self::faker()->boolean() ? self::faker()->dateTimeBetween('+1 month', '+6 months') : null,
            'comment' => self::faker()->text(),
        ];
    }

    protected static function getClass(): string
    {
        return CheckPayment::class;
    }
}

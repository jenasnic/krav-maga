<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\CashPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CashPayment>
 */
final class CashPaymentFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomElement([240, 120, 80, 60]),
            'date' => self::faker()->dateTimeBetween('-3 months', '-1 week'),
            'comment' => self::faker()->text(),
        ];
    }

    protected static function getClass(): string
    {
        return CashPayment::class;
    }
}

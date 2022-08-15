<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\PassPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<PassPayment>
 */
final class PassPaymentFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomElement([240, 120, 80, 60]),
            'date' => self::faker()->dateTimeBetween('-3 months', '-1 week'),
            'number' => 'PASS-'.self::faker()->numberBetween(1000, 9999),
            'comment' => self::faker()->text(),
        ];
    }

    protected static function getClass(): string
    {
        return PassPayment::class;
    }
}

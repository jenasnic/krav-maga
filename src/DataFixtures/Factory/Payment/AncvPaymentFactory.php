<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\AncvPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<AncvPayment>
 */
final class AncvPaymentFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomElement([240, 120, 80, 60]),
            'date' => self::faker()->dateTimeBetween('-3 months', '-1 week'),
            'number' => 'ANCV-'.self::faker()->numberBetween(1000000, 999999),
            'comment' => self::faker()->text(),
        ];
    }

    protected static function getClass(): string
    {
        return AncvPayment::class;
    }
}

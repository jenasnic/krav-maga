<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\HelloAssoPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<HelloAssoPayment>
 */
final class HelloAssoPaymentFactory extends ModelFactory
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
        return HelloAssoPayment::class;
    }
}

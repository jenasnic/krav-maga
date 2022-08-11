<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\TransferPayment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<TransferPayment>
 */
final class TransferPaymentFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomElement([240, 120, 80, 60]),
            'date' => self::faker()->dateTimeBetween('-3 months', '-1 week'),
            'label' => 'VIR '.self::faker()->word(),
            'comment' => self::faker()->text(),
        ];
    }

    protected static function getClass(): string
    {
        return TransferPayment::class;
    }
}

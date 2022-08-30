<?php

namespace App\DataFixtures\Factory;

use App\Entity\ReEnrollmentToken;
use DateTime;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<ReEnrollmentToken>
 */
final class ReEnrollmentTokenFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'id' => substr(uniqid().bin2hex(random_bytes(20)), 0, 55),
            'expiresAt' => new DateTime('+2 months'),
        ];
    }

    protected static function getClass(): string
    {
        return ReEnrollmentToken::class;
    }
}

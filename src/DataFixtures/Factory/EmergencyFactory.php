<?php

namespace App\DataFixtures\Factory;

use App\Entity\Emergency;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Emergency>
 */
final class EmergencyFactory extends ModelFactory
{
    private Generator $faker;

    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    protected static function getClass(): string
    {
        return Emergency::class;
    }
}

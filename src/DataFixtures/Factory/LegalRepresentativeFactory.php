<?php

namespace App\DataFixtures\Factory;

use App\Entity\LegalRepresentative;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<LegalRepresentative>
 */
final class LegalRepresentativeFactory extends ModelFactory
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
        ];
    }

    protected static function getClass(): string
    {
        return LegalRepresentative::class;
    }
}

<?php

namespace App\DataFixtures\Factory\Payment;

use App\Entity\Payment\PriceOption;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<PriceOption>
 */
final class PriceOptionFactory extends ModelFactory
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
            'label' => $this->faker->words(4, true),
        ];
    }

    protected static function getClass(): string
    {
        return PriceOption::class;
    }
}

<?php

namespace App\DataFixtures\Factory;

use App\Entity\RegistrationInfo;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<RegistrationInfo>
 */
final class RegistrationInfoFactory extends ModelFactory
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
            'purpose' => PurposeFactory::random()->object(),
            'medicalCertificateUrl' => $this->faker->filePath(),
            'copyrightAuthorization' => $this->faker->boolean(80),
        ];
    }

    protected static function getClass(): string
    {
        return RegistrationInfo::class;
    }
}

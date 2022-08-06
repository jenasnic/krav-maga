<?php

namespace App\DataFixtures\Factory;

use App\Entity\Registration;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Registration>
 */
final class RegistrationFactory extends ModelFactory
{
    private string $attestationModel = __DIR__.'/data/test.pdf';

    private Generator $faker;

    public function __construct(
        protected Filesystem $filesystem,
        protected string $uploadPath,
    ) {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        $fileName = str_replace('.', '', uniqid('', true)).'.pdf';
        $filePath = $this->uploadPath.$fileName;
        $this->filesystem->copy($this->attestationModel, $filePath);

        $registredAt = $this->faker->dateTimeBetween('-2 months', '-1 week');

        return [
            'comment' => $this->faker->text(),
            'privateNote' => $this->faker->text(),
            'licenceNumber' => $this->faker->numberBetween(100000, 999999),
            'licenceDate' => $registredAt,
            'ffkPassport' => $this->faker->boolean(20),
            'medicalCertificateUrl' => $filePath,
            'registeredAt' => $registredAt,
            'copyrightAuthorization' => $this->faker->boolean(80),
            'purpose' => PurposeFactory::random()->object(),
            'emergency' => EmergencyFactory::new(),
            'adherent' => AdherentFactory::new(),
            'verified' => $this->faker->boolean(80),
        ];
    }

    protected static function getClass(): string
    {
        return Registration::class;
    }
}

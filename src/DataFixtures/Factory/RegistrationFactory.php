<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Payment\PriceOptionFactory;
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

        $registeredAt = $this->faker->dateTimeBetween('-2 months', '-1 week');

        $withLegalRepresentative = $this->faker->boolean(30);
        $adherentAttributes = [];
        if ($withLegalRepresentative) {
            $adherentAttributes['birthDate'] = $this->faker->dateTimeBetween('-18 years', '-11 years');
        }

        return [
            'comment' => $this->faker->text(),
            'privateNote' => $this->faker->text(),
            'licenseNumber' => $this->faker->numberBetween(100000, 999999),
            'licenseDate' => $registeredAt,
            'ffkPassport' => $this->faker->boolean(20),
            'medicalCertificateUrl' => $filePath,
            'registeredAt' => $registeredAt,
            'copyrightAuthorization' => $this->faker->boolean(80),
            'purpose' => PurposeFactory::random()->object(),
            'priceOption' => PriceOptionFactory::random()->object(),
            'emergency' => EmergencyFactory::new(),
            'adherent' => AdherentFactory::new($adherentAttributes),
            'verified' => $this->faker->boolean(80),
            'withLegalRepresentative' => $withLegalRepresentative,
            'legalRepresentative' => $withLegalRepresentative ? LegalRepresentativeFactory::new() : null,
        ];
    }

    protected static function getClass(): string
    {
        return Registration::class;
    }
}

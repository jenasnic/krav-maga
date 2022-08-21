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
    private string $fileModel = __DIR__.'/data/test.pdf';

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
        $medicalCertificatePath = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
        $this->filesystem->copy($this->fileModel, $medicalCertificatePath);

        $licenceFormPath = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
        $this->filesystem->copy($this->fileModel, $licenceFormPath);

        $registeredAt = $this->faker->dateTimeBetween('-2 months', '-1 week');

        $withLegalRepresentative = $this->faker->boolean(30);
        $adherentAttributes = [];
        if ($withLegalRepresentative) {
            $adherentAttributes['birthDate'] = $this->faker->dateTimeBetween('-18 years', '-11 years');
        }

        $usePass15 = $this->faker->boolean(30);
        $usePass50 = $this->faker->boolean(30);

        if ($usePass15) {
            $pass15Path = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
            $this->filesystem->copy($this->fileModel, $pass15Path);
        }
        if ($usePass50) {
            $pass50Path = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
            $this->filesystem->copy($this->fileModel, $pass50Path);
        }

        return [
            'adherent' => AdherentFactory::new($adherentAttributes),
            'comment' => $this->faker->text(),
            'copyrightAuthorization' => $this->faker->boolean(80),
            'emergency' => EmergencyFactory::new(),
            'legalRepresentative' => $withLegalRepresentative ? LegalRepresentativeFactory::new() : null,
            'licenceDate' => $registeredAt,
            'licenceFormUrl' => $licenceFormPath,
            'licenceNumber' => $this->faker->numberBetween(100000, 999999),
            'medicalCertificateUrl' => $medicalCertificatePath,
            'pass15Url' => $usePass15 ? $pass15Path : null,
            'pass50Url' => $usePass50 ? $pass50Path : null,
            'priceOption' => PriceOptionFactory::random()->object(),
            'privateNote' => $this->faker->text(),
            'purpose' => PurposeFactory::random()->object(),
            'registeredAt' => $registeredAt,
            'usePass15' => $usePass15,
            'usePass50' => $usePass50,
            'verified' => $this->faker->boolean(80),
            'withLegalRepresentative' => $withLegalRepresentative,
        ];
    }

    protected static function getClass(): string
    {
        return Registration::class;
    }
}

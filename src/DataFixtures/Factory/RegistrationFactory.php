<?php

namespace App\DataFixtures\Factory;

use App\Entity\Payment\PriceOption;
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

        $usePassCitizen = $this->faker->boolean(30);
        $usePassSport = $this->faker->boolean(30);

        if ($usePassCitizen) {
            $passCitizenPath = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
            $this->filesystem->copy($this->fileModel, $passCitizenPath);
        }
        if ($usePassSport) {
            $passSportPath = $this->uploadPath.str_replace('.', '', uniqid('', true)).'.pdf';
            $this->filesystem->copy($this->fileModel, $passSportPath);
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
            'passCitizenUrl' => $usePassCitizen ? $passCitizenPath : null,
            'passSportUrl' => $usePassSport ? $passSportPath : null,
            'privateNote' => $this->faker->text(),
            'purpose' => PurposeFactory::random()->object(),
            'registeredAt' => $registeredAt,
            'usePassCitizen' => $usePassCitizen,
            'usePassSport' => $usePassSport,
            'verified' => $this->faker->boolean(80),
            'withLegalRepresentative' => $withLegalRepresentative,
        ];
    }

    protected function initialize(): self
    {
        return $this->afterInstantiate(function (Registration $registration, array $attributes) {
            if (!array_key_exists('priceOption', $attributes)) {
                /** @var PriceOption $priceOption */
                $priceOption = $this->faker->randomElement($registration->getSeason()->getPriceOptions()->toArray());
                $registration->setPriceOption($priceOption);
            }
        });
    }

    protected static function getClass(): string
    {
        return Registration::class;
    }
}

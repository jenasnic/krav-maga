<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\RegistrationFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\DataFixtures\Payment\PriceOptionFixtures;
use App\Entity\Payment\PriceOption;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Zenstruck\Foundry\Proxy;

class RegistrationFixtures extends Fixture implements DependentFixtureInterface
{
    public const REGISTRATION_COUNT_FOR_PRICING = [
        PriceOptionFixtures::PRICE_1 => 3,
        PriceOptionFixtures::PRICE_2 => 2,
        PriceOptionFixtures::PRICE_3 => 2,
        PriceOptionFixtures::PRICE_4 => 2,
        PriceOptionFixtures::PRICE_5 => 1,
    ];

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $isReEnrollmentAvailable = false;

        /** @var Proxy<Season> $season */
        foreach (SeasonFactory::all() as $season) {
            $season = $season->object();
            $registrationDate = new \DateTime(sprintf('%s-09-15', $season->getLabel()));

            if ($season->isActive()) {
                /** @var PriceOption $priceOption */
                foreach ($season->getPriceOptions() as $priceOption) {
                    $count = self::REGISTRATION_COUNT_FOR_PRICING[$priceOption->getLabel()];
                    RegistrationFactory::createMany($count, [
                        'season' => $season,
                        'registeredAt' => $registrationDate,
                        'priceOption' => $priceOption,
                        'reEnrollment' => $this->faker->boolean(),
                    ]);
                }
            } else {
                RegistrationFactory::createMany($this->faker->numberBetween(5, 8), [
                    'season' => $season,
                    'registeredAt' => $registrationDate,
                    'reEnrollment' => $isReEnrollmentAvailable && $this->faker->boolean(),
                ]);
            }

            $isReEnrollmentAvailable = true;
        }
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            PurposeFixtures::class,
            PriceOptionFixtures::class,
            SeasonFixtures::class,
        ];
    }
}

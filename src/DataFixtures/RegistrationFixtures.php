<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\Payment\PriceOptionFactory;
use App\DataFixtures\Factory\RegistrationFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\DataFixtures\Payment\PriceOptionFixtures;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RegistrationFixtures extends Fixture implements DependentFixtureInterface
{
    public const REGISTRATION_COUNT_FOR_PRICING = [
        PriceOptionFixtures::PRICE_1 => 9,
        PriceOptionFixtures::PRICE_2 => 6,
        PriceOptionFixtures::PRICE_3 => 6,
        PriceOptionFixtures::PRICE_4 => 4,
        PriceOptionFixtures::PRICE_5 => 4,
        PriceOptionFixtures::PRICE_6 => 3,
    ];

    public function load(ObjectManager $manager): void
    {
        $season2020 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2020]);
        RegistrationFactory::createMany(5, [
            'season' => $season2020,
            'registeredAt' => new DateTime('2020-09-15'),
        ]);
        $season2021 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2021]);
        RegistrationFactory::createMany(5, [
            'season' => $season2021,
            'registeredAt' => new DateTime('2021-09-15'),
        ]);

        $season2022 = SeasonFactory::find(['label' => SeasonFixtures::SEASON_2022]);
        foreach (PriceOptionFactory::all() as $priceOption) {
            $count = self::REGISTRATION_COUNT_FOR_PRICING[$priceOption->getLabel()];

            RegistrationFactory::createMany($count, [
                'season' => $season2022,
                'registeredAt' => new DateTime('2022-09-15'),
                'priceOption' => $priceOption,
            ]);
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

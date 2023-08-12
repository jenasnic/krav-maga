<?php

namespace App\DataFixtures\Payment;

use App\DataFixtures\Factory\Payment\PriceOptionFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\DataFixtures\SeasonFixtures;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Proxy;

class PriceOptionFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRICE_1 = 'Full access';
    public const PRICE_2 = 'KMix-MMA';
    public const PRICE_3 = 'Cardio Fit';
    public const PRICE_4 = 'Defense Femmes';
    public const PRICE_5 = 'Defense Ados';

    public const BASE_PRICE = [
        self::PRICE_1 => 200,
        self::PRICE_2 => 160,
        self::PRICE_3 => 160,
        self::PRICE_4 => 100,
        self::PRICE_5 => 70,
    ];

    public function load(ObjectManager $manager): void
    {
        $increment = 0;

        /** @var Proxy<Season> $season */
        foreach (SeasonFactory::all() as $season) {
            PriceOptionFactory::createOne([
                'label' => self::PRICE_1,
                'amount' => self::BASE_PRICE[self::PRICE_1] + (10 * $increment),
                'rank' => 1,
                'season' => $season,
            ]);
            PriceOptionFactory::createOne([
                'label' => self::PRICE_2,
                'amount' => self::BASE_PRICE[self::PRICE_2] + (10 * $increment),
                'rank' => 2,
                'season' => $season,
            ]);
            PriceOptionFactory::createOne([
                'label' => self::PRICE_3,
                'amount' => self::BASE_PRICE[self::PRICE_3] + (10 * $increment),
                'rank' => 3,
                'season' => $season,
            ]);

            if ($season->isActive()) {
                PriceOptionFactory::createOne([
                    'label' => self::PRICE_4,
                    'amount' => self::BASE_PRICE[self::PRICE_4] + (10 * $increment),
                    'rank' => 4,
                    'season' => $season,
                ]);
                PriceOptionFactory::createOne([
                    'label' => self::PRICE_5,
                    'amount' => self::BASE_PRICE[self::PRICE_5] + (10 * $increment),
                    'rank' => 6,
                    'season' => $season,
                ]);
            }

            $increment++;
        }
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures\Payment;

use App\DataFixtures\Factory\Payment\PriceOptionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PriceOptionFixtures extends Fixture
{
    public const PRICE_1 = 'Full access';
    public const PRICE_2 = 'KMix-MMA Defense & Krav Maga Self Defense';
    public const PRICE_3 = 'Cardio Fit Krav & Krav Defense Training (Defense Femmes inclus)';
    public const PRICE_4 = 'Cardio Fit Krav';
    public const PRICE_5 = 'Defense Ados 11-15 ans (KMix-MMA Defense inclus)';
    public const PRICE_6 = 'Defense Femmes';

    public function load(ObjectManager $manager): void
    {
        PriceOptionFactory::createOne([
            'label' => self::PRICE_1,
            'amount' => 220,
            'rank' => 1,
        ]);
        PriceOptionFactory::createOne([
            'label' => self::PRICE_2,
            'amount' => 180,
            'rank' => 2,
        ]);
        PriceOptionFactory::createOne([
            'label' => self::PRICE_3,
            'amount' => 180,
            'rank' => 3,
        ]);
        PriceOptionFactory::createOne([
            'label' => self::PRICE_4,
            'amount' => 120,
            'rank' => 4,
        ]);
        PriceOptionFactory::createOne([
            'label' => self::PRICE_5,
            'amount' => 120,
            'rank' => 5,
        ]);
        PriceOptionFactory::createOne([
            'label' => self::PRICE_6,
            'amount' => 80,
            'rank' => 6,
        ]);
    }
}

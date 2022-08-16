<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\SeasonFactory;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public const SEASON_2020 = '2020';
    public const SEASON_2021 = '2021';
    public const SEASON_2022 = '2022';

    public function load(ObjectManager $manager): void
    {
        SeasonFactory::createOne([
            'label' => self::SEASON_2020,
            'startDate' => new DateTime('2020-09-01'),
            'endDate' => new DateTime('2021-08-31'),
            'active' => false,
        ]);
        SeasonFactory::createOne([
            'label' => self::SEASON_2021,
            'startDate' => new DateTime('2021-09-01'),
            'endDate' => new DateTime('2022-08-31'),
            'active' => false,
        ]);
        SeasonFactory::createOne([
            'label' => self::SEASON_2022,
            'startDate' => new DateTime('2022-09-01'),
            'endDate' => new DateTime('2023-08-31'),
            'active' => true,
        ]);
    }
}

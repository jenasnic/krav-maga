<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\SeasonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public const SEASON_COUNT = 3;

    public function load(ObjectManager $manager): void
    {
        for ($i = self::SEASON_COUNT; $i > 0; --$i) {
            $year = (new \DateTime(sprintf('-%d year', $i)))->format('Y');
            SeasonFactory::createOne([
                'label' => $year,
                'startDate' => new \DateTime(sprintf('%s-09-01', $year)),
                'endDate' => new \DateTime(sprintf('%s-08-31', (int) $year + 1)),
                'active' => (1 === $i),
            ]);
        }
    }
}

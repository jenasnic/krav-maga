<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\SeasonFactory;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $currentYear = (int) (new DateTime('-2 years'))->format('Y');

        for ($i = 2; $i >= 0; --$i) {
            SeasonFactory::createOne([
                'label' => $currentYear,
                'startDate' => new DateTime($currentYear.'-09-01'),
                'endDate' => new DateTime(++$currentYear.'-08-31'),
                'active' => (0 === $i),
            ]);
        }
    }
}

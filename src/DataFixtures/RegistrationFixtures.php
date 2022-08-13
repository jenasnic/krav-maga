<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\RegistrationFactory;
use App\DataFixtures\Factory\SeasonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RegistrationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $currentSeason = SeasonFactory::find(['active' => true]);

        RegistrationFactory::createMany(35, ['season' => $currentSeason]);
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            PurposeFixtures::class,
            SeasonFixtures::class,
        ];
    }
}

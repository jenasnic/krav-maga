<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\RegistrationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RegistrationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        RegistrationFactory::createMany(35);
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            PurposeFixtures::class,
        ];
    }
}

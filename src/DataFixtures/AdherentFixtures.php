<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\AdherentFactory;
use App\Enum\GenderEnum;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdherentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AdherentFactory::createOne([
            'firstName' => 'Rei',
            'lastName' => 'ICHIDO',
            'gender' => GenderEnum::MALE,
            'birthDate' => new DateTime('-18 years'),
            'phone' => '01 11 11 11 11',
            'email' => 'rei.ichido@yopmail.com',
            'verified' => true,
        ]);
        AdherentFactory::createOne([
            'firstName' => 'Kawa',
            'lastName' => 'YUI',
            'gender' => GenderEnum::FEMALE,
            'birthDate' => new DateTime('-18 years'),
            'phone' => '02 22 22 22 22',
            'email' => 'kawa.yui@yopmail.com',
            'verified' => true,
        ]);

        AdherentFactory::createMany(15);
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

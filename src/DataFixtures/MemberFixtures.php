<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\MemberFactory;
use App\Enum\GenderEnum;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        MemberFactory::createOne([
            'firstName' => 'Rei',
            'lastName' => 'ICHIDO',
            'gender' => GenderEnum::MALE,
            'birthDate' => new DateTime('-18 years'),
            'phone' => '01 11 11 11 11',
            'email' => 'rei.ichido@yopmail.com',
        ]);
        MemberFactory::createOne([
            'firstName' => 'Kawa',
            'lastName' => 'YUI',
            'gender' => GenderEnum::FEMALE,
            'birthDate' => new DateTime('-18 years'),
            'phone' => '02 22 22 22 22',
            'email' => 'kawa.yui@yopmail.com',
        ]);

        MemberFactory::createMany(15);
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

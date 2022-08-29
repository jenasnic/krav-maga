<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ReEnrollmentTokenFactory;
use App\DataFixtures\Factory\RegistrationFactory;
use App\Entity\Registration;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Proxy;

class ReEnrollmentTokenFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $registration2021 = RegistrationFactory::findBy(['registeredAt' => new DateTime('2021-09-15')]);

        /** @var Proxy<Registration> $registration */
        foreach ($registration2021 as $registration) {
            ReEnrollmentTokenFactory::createOne([
                'adherent' => $registration->getAdherent(),
            ]);
        }
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            RegistrationFixtures::class,
        ];
    }
}

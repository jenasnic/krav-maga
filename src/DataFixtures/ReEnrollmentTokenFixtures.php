<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ReEnrollmentTokenFactory;
use App\DataFixtures\Factory\RegistrationFactory;
use App\DataFixtures\Factory\SeasonFactory;
use App\Entity\Registration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Proxy;

class ReEnrollmentTokenFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $lastSeason = SeasonFactory::find(['label' => (new \DateTime('-1year'))->format('Y')])->object();
        $previousSeason = SeasonFactory::find(['label' => (new \DateTime('-2year'))->format('Y')])->object();

        /** @var array<Proxy<Registration>> $previousSeasonRegistrations */
        $previousSeasonRegistrations = RegistrationFactory::findBy(['season' => $previousSeason->getId()]);

        $previousSeasonAdherents = array_map(
            fn (Proxy $registration) => $registration->getAdherent(),
            $previousSeasonRegistrations
        );

        foreach ($previousSeasonAdherents as $adherent) {
            ReEnrollmentTokenFactory::createOne([
                'adherent' => $adherent,
                'season' => $lastSeason,
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

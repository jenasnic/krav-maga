<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\PurposeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PurposeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        PurposeFactory::createOne([
            'label' => 'Découverte',
            'rank' => 1,
        ]);
        PurposeFactory::createOne([
            'label' => 'Remise en forme',
            'rank' => 2,
        ]);
        PurposeFactory::createOne([
            'label' => 'Gagner en confiance en soi',
            'rank' => 3,
        ]);
        PurposeFactory::createOne([
            'label' => 'Apprendre à se défendre',
            'rank' => 4,
        ]);
        PurposeFactory::createOne([
            'label' => 'Passage de ceinture',
            'rank' => 5,
        ]);
    }
}

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
            'label' => 'Remise en forme',
            'rank' => 1,
        ]);
        PurposeFactory::createOne([
            'label' => 'Passage de ceinture',
            'rank' => 2,
        ]);
        PurposeFactory::createOne([
            'label' => 'Découverte',
            'rank' => 3,
        ]);
    }
}

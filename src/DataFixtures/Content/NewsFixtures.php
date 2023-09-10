<?php

namespace App\DataFixtures\Content;

use App\DataFixtures\Factory\Content\NewsFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public const NEWS_COUNT = 3;

    public function load(ObjectManager $manager): void
    {
        for ($i = self::NEWS_COUNT; $i > 0; --$i) {
            NewsFactory::createOne(['rank' => $i]);
        }
    }
}

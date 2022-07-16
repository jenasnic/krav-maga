<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\UserFactory;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@yopmail.com',
            'role' => RoleEnum::ROLE_ADMIN,
            'enabled' => true,
        ]);

        UserFactory::createOne([
            'email' => 'user@yopmail.com',
            'enabled' => true,
        ]);
    }
}

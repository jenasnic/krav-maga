<?php

namespace App\DataFixtures\Factory;

use App\Entity\Member;
use App\Enum\GenderEnum;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Member>
 */
final class MemberFactory extends ModelFactory
{
    private int $counter = 0;

    private Generator $faker;

    private AsciiSlugger $slugger;

    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
        $this->slugger = new AsciiSlugger('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        $email = sprintf(
            '%s.%s.%d@yopmail.com',
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
            ++$this->counter
        );

        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $this->faker->randomElement(GenderEnum::getAll()),
            'birthDate' => $this->faker->dateTimeBetween('-55 years', '-16 years'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $email,
            'registrationInfo' => RegistrationInfoFactory::new(),
        ];
    }

    protected static function getClass(): string
    {
        return Member::class;
    }
}

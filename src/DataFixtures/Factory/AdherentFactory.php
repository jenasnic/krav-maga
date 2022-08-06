<?php

namespace App\DataFixtures\Factory;

use App\Entity\Adherent;
use App\Enum\GenderEnum;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Adherent>
 */
final class AdherentFactory extends ModelFactory
{
    private string $malePicture = __DIR__.'/data/male.jpg';
    private string $femalePicture = __DIR__.'/data/female.jpg';

    private int $counter = 0;

    private Generator $faker;

    private AsciiSlugger $slugger;

    public function __construct(
        protected Filesystem $filesystem,
        protected string $uploadPath,
    ) {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
        $this->slugger = new AsciiSlugger('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        /** @var string $gender */
        $gender = $this->faker->randomElement(GenderEnum::getAll());
        $firstName = $this->faker->firstName(strtolower($gender));
        $lastName = $this->faker->lastName();

        $email = sprintf(
            '%s.%s.%d@yopmail.com',
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
            ++$this->counter
        );

        $pictureFixture = (GenderEnum::MALE === $gender) ? $this->malePicture : $this->femalePicture;
        $fileName = str_replace('.', '', uniqid('', true)).'.jpg';
        $filePath = $this->uploadPath.$fileName;
        $this->filesystem->copy($pictureFixture, $filePath);

        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $gender,
            'birthDate' => $this->faker->dateTimeBetween('-55 years', '-16 years'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $email,
            'pseudonym' => $firstName.substr($lastName, 0, 1),
            'pictureUrl' => $filePath,
        ];
    }

    protected static function getClass(): string
    {
        return Adherent::class;
    }
}

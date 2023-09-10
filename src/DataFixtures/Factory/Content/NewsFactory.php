<?php

namespace App\DataFixtures\Factory\Content;

use App\Entity\Content\News;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<News>
 */
final class NewsFactory extends ModelFactory
{
    private string $newsPicture = __DIR__.'/../data/news.jpg';

    private Generator $faker;

    public function __construct(
        protected Filesystem $filesystem,
        protected string $uploadPath,
    ) {
        parent::__construct();

        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        $filePath = $this->uploadPath.News::PICTURE_FOLDER.str_replace('.', '', uniqid('', true)).'.jpg';
        $this->filesystem->copy($this->newsPicture, $filePath);

        return [
            'title' => $this->faker->words(4, true),
            'content' => $this->faker->text(),
            'details' => $this->faker->words(3, true),
            'active' => $this->faker->boolean(80),
            'pictureUrl' => $filePath,
        ];
    }

    protected static function getClass(): string
    {
        return News::class;
    }
}

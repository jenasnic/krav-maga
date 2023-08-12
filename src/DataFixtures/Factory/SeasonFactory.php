<?php

namespace App\DataFixtures\Factory;

use App\Entity\Season;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Season>
 */
final class SeasonFactory extends ModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        $date = self::faker()->dateTimeBetween('-4 years', '+1 year');
        $year = $date->format('Y');
        $nextYear = $date->add(\DateInterval::createFromDateString('+1 year'))->format('Y');

        return [
            'label' => $year,
            'startDate' => new \DateTime($year.'-09-01'),
            'endDate' => new \DateTime($nextYear.'-08-31'),
        ];
    }

    protected static function getClass(): string
    {
        return Season::class;
    }
}

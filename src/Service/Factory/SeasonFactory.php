<?php

namespace App\Service\Factory;

use App\Entity\Season;
use App\Exception\SeasonAlreadyDefinedException;
use App\Repository\SeasonRepository;
use DateTime;

class SeasonFactory
{
    public function __construct(protected SeasonRepository $seasonRepository)
    {
    }

    /**
     * @throws SeasonAlreadyDefinedException
     */
    public function createNew(): Season
    {
        $currentYear = (new DateTime())->format('Y');

        if ($this->seasonRepository->existForYear($currentYear)) {
            throw new SeasonAlreadyDefinedException(sprintf('A season is already set for year %s', $currentYear));
        }

        return new Season($currentYear);
    }
}

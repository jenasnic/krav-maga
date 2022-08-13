<?php

namespace App\Service\Factory;

use App\Entity\Adherent;
use App\Entity\Registration;
use App\Exception\NoActiveSeasonException;
use App\Repository\SeasonRepository;

class RegistrationFactory
{
    public function __construct(protected SeasonRepository $seasonRepository)
    {
    }

    /**
     * @throws NoActiveSeasonException
     */
    public function createNew(): Registration
    {
        $season = $this->seasonRepository->getActiveSeason();
        if (null === $season) {
            throw new NoActiveSeasonException();
        }

        return new Registration(new Adherent(), $season);
    }
}

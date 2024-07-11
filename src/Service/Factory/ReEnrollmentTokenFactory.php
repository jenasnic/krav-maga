<?php

namespace App\Service\Factory;

use App\Entity\Adherent;
use App\Entity\ReEnrollmentToken;
use App\Entity\Season;

class ReEnrollmentTokenFactory
{
    public function create(Adherent $adherent, Season $season): ReEnrollmentToken
    {
        return new ReEnrollmentToken(
            substr(uniqid().bin2hex(random_bytes(20)), 0, 55),
            $adherent,
            $season,
            new \DateTime('+3 months'),
        );
    }
}

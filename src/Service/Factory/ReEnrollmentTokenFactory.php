<?php

namespace App\Service\Factory;

use App\Entity\Adherent;
use App\Entity\ReEnrollmentToken;

class ReEnrollmentTokenFactory
{
    public function create(Adherent $adherent): ReEnrollmentToken
    {
        return new ReEnrollmentToken(
            substr(uniqid().bin2hex(random_bytes(20)), 0, 55),
            $adherent,
            new \DateTime('+3 months'),
        );
    }
}

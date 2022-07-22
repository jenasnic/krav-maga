<?php

namespace App\Domain\Command\Front;

use App\Entity\Adherent;

class RegistrationCommand
{
    public function __construct(public Adherent $adherent)
    {
    }
}

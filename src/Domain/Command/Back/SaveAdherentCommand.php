<?php

namespace App\Domain\Command\Back;

use App\Entity\Adherent;

class SaveAdherentCommand
{
    public function __construct(public Adherent $adherent)
    {
    }
}

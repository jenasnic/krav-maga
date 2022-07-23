<?php

namespace App\Domain\Command\Back;

use App\Entity\Adherent;

class RemoveAdherentCommand
{
    public function __construct(public Adherent $adherent)
    {
    }
}

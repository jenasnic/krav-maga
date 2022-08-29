<?php

namespace App\Domain\Command\Back;

use App\Entity\Season;

class ActivateSeasonCommand
{
    public function __construct(public Season $season)
    {
    }
}

<?php

namespace App\Domain\Command\Front;

use App\Entity\Adherent;
use Symfony\Component\HttpFoundation\Request;

class ConfirmRegistrationCommand
{
    public function __construct(
        public Adherent $adherent,
        public Request $request,
    ) {
    }
}

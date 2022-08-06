<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;

class NewRegistrationCommand
{
    public function __construct(public Registration $registration)
    {
    }
}

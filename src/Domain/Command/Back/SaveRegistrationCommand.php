<?php

namespace App\Domain\Command\Back;

use App\Entity\Registration;

class SaveRegistrationCommand
{
    public function __construct(public Registration $registration)
    {
    }
}

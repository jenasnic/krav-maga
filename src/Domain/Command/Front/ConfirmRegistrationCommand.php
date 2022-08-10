<?php

namespace App\Domain\Command\Front;

use App\Entity\Registration;
use Symfony\Component\HttpFoundation\Request;

class ConfirmRegistrationCommand
{
    public function __construct(
        public Registration $registration,
        public Request $request,
    ) {
    }
}

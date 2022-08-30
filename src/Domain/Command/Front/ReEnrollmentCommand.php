<?php

namespace App\Domain\Command\Front;

use App\Entity\ReEnrollmentToken;
use App\Entity\Registration;

class ReEnrollmentCommand
{
    public function __construct(
        public Registration $registration,
        public ReEnrollmentToken $reEnrollmentToken,
    ) {
    }
}

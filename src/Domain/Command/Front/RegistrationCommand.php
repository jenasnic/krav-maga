<?php

namespace App\Domain\Command\Front;

use App\Entity\Member;

class RegistrationCommand
{
    public function __construct(public Member $member)
    {
    }
}

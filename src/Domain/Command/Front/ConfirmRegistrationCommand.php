<?php

namespace App\Domain\Command\Front;

use App\Entity\Member;
use Symfony\Component\HttpFoundation\Request;

class ConfirmRegistrationCommand
{
    public function __construct(
        public Member $member,
        public Request $request,
    ) {
    }
}

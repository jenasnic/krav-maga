<?php

namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ReEnrollment
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;
}

<?php

namespace App\Domain\Command\Front;

use Symfony\Component\Validator\Constraints as Assert;

class ContactFormCommand
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $message = null;

    public ?string $ip = null;
}

<?php

namespace App\Service\Email;

use Twig\Environment;

class EmailBuilderFactory
{
    public function __construct(
        protected Environment $twig,
        protected string $mailerSender
    ) {
    }

    public function createEmailBuilder(): EmailBuilder
    {
        return new EmailBuilder($this->twig, $this->mailerSender);
    }
}

<?php

namespace App\Service\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender
{
    public function __construct(
        protected MailerInterface $mailer,
        protected EmailBuilderFactory $emailBuilderFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function send(string $template, string $receipt, array $context = []): void
    {
        $emailBuilder = $this->emailBuilderFactory->createEmailBuilder();

        $emailBuilder
            ->useTemplate($template, $context)
            ->fromDefault()
            ->to($receipt)
        ;

        $this->mailer->send($emailBuilder->getEmail());
    }

    public function sendEmail(Email $email): void
    {
        $this->mailer->send($email);
    }
}

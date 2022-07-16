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
     * @param array<string> $receipts
     * @param array<string, mixed> $context
     */
    public function send(string $template, array $receipts, array $context = []): void
    {
        $emailBuilder = $this->emailBuilderFactory->createEmailBuilder();

        $emailBuilder
            ->useTemplate($template, $context)
            ->fromDefault()
            ->to($receipts)
        ;

        $this->mailer->send($emailBuilder->getEmail());
    }

    public function sendEmail(Email $email): void
    {
        $this->mailer->send($email);
    }
}

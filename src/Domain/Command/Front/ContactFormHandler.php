<?php

namespace App\Domain\Command\Front;

use App\Service\Email\EmailSender;

final class ContactFormHandler
{
    public function __construct(
        private readonly EmailSender $emailSender,
        private readonly string $mailerContact,
    ) {
    }

    public function handle(ContactFormCommand $command): void
    {
        /** @var string $contactEmail */
        $contactEmail = $command->email;

        $this->emailSender->send(
            'email/contact_form.html.twig',
            $this->mailerContact,
            [
                'email' => $command->email,
                'message' => $command->message,
                'ip' => $command->ip,
            ],
        );

        $this->emailSender->send(
            'email/contact_form_notification.html.twig',
            $contactEmail,
        );
    }
}

<?php

namespace App\Service\Email;

use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailBuilder
{
    private Email $email;

    public function __construct(
        protected Environment $twig,
        protected string $mailerSender,
        protected string $mailerContact,
    ) {
        $this->email = new Email();
    }

    /**
     * @param array<string, mixed> $context
     */
    public function useTemplate(string $template, array $context = []): self
    {
        $template = $this->twig->load($template);

        $this->email
            ->subject($template->renderBlock('subject', $context))
            ->html($template->renderBlock('html', $context))
            ->text($template->renderBlock('text', $context))
        ;

        return $this;
    }

    public function fromDefault(): self
    {
        $this->from($this->mailerSender);

        return $this;
    }

    public function from(string $sender): self
    {
        $this->email->from($sender);

        return $this;
    }

    public function to(string $receipt): self
    {
        $this->email->to($receipt);

        return $this;
    }

    public function copy(): self
    {
        $this->email->addBcc($this->mailerContact);

        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}

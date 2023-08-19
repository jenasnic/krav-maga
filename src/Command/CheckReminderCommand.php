<?php

namespace App\Command;

use App\Repository\Payment\CheckPaymentRepository;
use App\Service\Email\EmailSender;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:check-reminder')]
final class CheckReminderCommand extends Command
{
    public function __construct(
        private readonly CheckPaymentRepository $checkPaymentRepository,
        private readonly EmailSender $emailSender,
        private readonly string $mailerContact,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send reminder for check to cash.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $checks = $this->checkPaymentRepository->findCheckToCash();

            if (!empty($checks)) {
                $this->emailSender->send(
                    'email/check_to_cash.html.twig',
                    $this->mailerContact,
                    ['checks' => $checks],
                );

                $io->success('Check reminders sent!');
            } else {
                $io->success('No reminder to send!');
            }
        } catch (\Exception $e) {
            $io->error(sprintf('An error occurs : %s', $e->getMessage()));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

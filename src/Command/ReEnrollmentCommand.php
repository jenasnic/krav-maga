<?php

namespace App\Command;

use App\Repository\SeasonRepository;
use App\Service\Notifier\ReEnrollmentNotifier;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:re-enrollment')]
final class ReEnrollmentCommand extends Command
{
    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly ReEnrollmentNotifier $reEnrollmentNotifier,
        private readonly int $mailerMaxPacketSize,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send re-enrollment email to adherent (use max packet size define in .env to avoid "Mails peer session limit").')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit on sent emails to avoid "Mails peer session limit" error (override MAILER_MAX_PACKET_SIZE from .env).')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var int $limit */
        $limit = $input->getArgument('limit') ?? $this->mailerMaxPacketSize;

        try {
            $season = $this->seasonRepository->getActiveSeason();

            if (null === $season) {
                $io->error('No active season found');

                return self::FAILURE;
            }

            $count = $this->reEnrollmentNotifier->notify($limit);

            $io->success(sprintf('%d re-enrollement emails sent!', $count));
        } catch (\Exception $e) {
            $io->error(sprintf('An error occurs : %s', $e->getMessage()));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

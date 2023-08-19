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
    public const DEFAULT_SENT_EMAIL_LIMIT = 10;

    public function __construct(
        private readonly SeasonRepository $seasonRepository,
        private readonly ReEnrollmentNotifier $reEnrollmentNotifier,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send re-enrollment email to adherent (send emails as a pack of 10 to avoid "Mails peer session limit").')
            ->addArgument('year', InputArgument::REQUIRED, 'Year of season concerned for re-enrollment (should be season before active season).')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit on sent emails to avoid "Mails peer session limit" error (default 10).')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $year */
        $year = $input->getArgument('year');
        /** @var int $limit */
        $limit = $input->getArgument('limit') ?? self::DEFAULT_SENT_EMAIL_LIMIT;

        try {
            $season = $this->seasonRepository->getForYear($year);

            if (null === $season) {
                $io->error(sprintf('Season %s not found', $year));

                return self::FAILURE;
            }

            $count = $this->reEnrollmentNotifier->notify($season, $limit);

            $io->success(sprintf('%d re-enrollement emails sent!', $count));
        } catch (\Exception $e) {
            $io->error(sprintf('An error occurs : %s', $e->getMessage()));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

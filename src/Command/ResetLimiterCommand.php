<?php

namespace App\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsCommand(name: 'app:reset-limiter')]
final class ResetLimiterCommand extends Command
{
    public function __construct(
        protected RateLimiterFactory $loginLimiter,
        protected RateLimiterFactory $emailLimiter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Reset security limiter to unlock account.')
            ->addArgument('ip', InputArgument::REQUIRED, 'IP')
            ->addArgument('account', InputArgument::REQUIRED, 'Account')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            /** @var string $ip */
            $ip = $input->getArgument('ip');
            /** @var string $account */
            $account = $input->getArgument('account');

            $this->loginLimiter->create($ip)->reset();
            $this->loginLimiter->create($account)->reset();
            $this->emailLimiter->create('login_throttling')->reset();
        } catch (Exception $e) {
            $io->error(sprintf('An error occurs : %s', $e->getMessage()));

            return 1;
        }

        $io->success('Security limiter unlocked!');

        return 0;
    }
}

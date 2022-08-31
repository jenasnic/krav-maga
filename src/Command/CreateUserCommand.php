<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use App\Service\Email\EmailSender;
use Exception;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin')]
final class CreateUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EmailSender $emailSender,
        private readonly string $mailerContact,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create admin user with specified email.')
            ->addArgument('email', InputArgument::REQUIRED, 'User login')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $email = $input->getArgument('email');
            if (!is_string($email)) {
                throw new LogicException('invalid email');
            }

            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');

            $question = new Question('Enter password');
            $question->setHidden(true);
            $question->setHiddenFallback(false);

            $password = $helper->ask($input, $output, $question);
            if (!is_string($password)) {
                throw new LogicException('invalid password');
            }

            $user = new User();
            $user
                ->setEmail($email)
                ->setRole(RoleEnum::ROLE_ADMIN)
                ->setEnabled(true)
                ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ;

            $this->userRepository->add($user, true);

            $this->emailSender->send(
                'email/new_user.html.twig',
                $this->mailerContact,
                ['user' => $user],
            );
        } catch (Exception $e) {
            $io->error(sprintf('An error occurs : %s', $e->getMessage()));

            return 1;
        }

        $io->success('New user created successfully!');

        return 0;
    }
}

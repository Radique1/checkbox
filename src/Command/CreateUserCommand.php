<?php

declare(strict_types = 1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    aliases: ['app:add-user'],
    hidden: false
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usernameQuestion = new Question('Please enter your username: ');
        $passwordQuestion = new Question('Please enter your password: ');
        $passwordConfirmationQuestion = new Question('Please confirm your password: ');

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $username = $helper->ask($input, $output, $usernameQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);
        $passwordConfirmation = $helper->ask($input, $output, $passwordConfirmationQuestion);

        if ($password !== $passwordConfirmation) {
            $output->writeln('The password confirmation does not match');
            return 0;
        }

        $user = new User();
        $user = $user
            ->setUsername($username)
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ->setRoles([]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return 1;
    }
}

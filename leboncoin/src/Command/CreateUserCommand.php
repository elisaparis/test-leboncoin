<?php


namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';

    private $em;

    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        parent::__construct();

        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user with an email, password, firstname and lastname.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln([
            '',
            'Create a new user for leboncoin',
            '',
            '-------------------------------',
            '',
        ]);

        $helper = $this->getHelper('question');
        $questionEmail = new Question('Email : ', '');
        $questionEmail->setValidator(function ($answer) {
            if (!is_string($answer) || $answer === '') {
                throw new \RuntimeException(
                    'The email cannot be null.'
                );
            }
            else if (!preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $answer)) {
                throw new \RuntimeException(
                    'This email is not valid.'
                );
            }

            return $answer;
        });
        $questionEmail->setMaxAttempts(3);
        $email = $helper->ask($input, $output, $questionEmail);

        $questionPassword = new Question('Password : ', '');
        $questionPassword->setValidator(function ($answer) {
            if (!is_string($answer) || $answer === '') {
                throw new \RuntimeException(
                    'The password cannot be null.'
                );
            }
            else if (strlen($answer) < 5) {
                throw new \RuntimeException(
                    'The password has to be at least 5 characters long.'
                );
            }

            return $answer;
        });

        $questionEmail->setMaxAttempts(3);
        $questionPassword->setHidden(true);
        $questionPassword->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $questionPassword);

        $questionFirstname = new Question('Firstname : ', null);
        $firstname = $helper->ask($input, $output, $questionFirstname);

        $questionLastname = new Question('Lastname : ', null);
        $lastname = $helper->ask($input, $output, $questionLastname);

        $user = new User();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);

        $userPassword = $this->encoder->encodePassword($user, $password);
        $user->setPassword($userPassword);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User has been created successfully.');

    }

}

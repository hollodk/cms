<?php

namespace Mh\PageBundle\Command;

use App\Security\AppCustomAuthenticator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordCommand extends Command
{
    protected static $defaultName = 'app:password';

    private $passwordEncoder;
    private $container;
    private $em;

    public function __construct(ContainerInterface $container, LoggerInterface $logger, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->container = $container;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $container->get('doctrine.orm.default_entity_manager');

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'Email to change password')
            ->addArgument('password', InputArgument::REQUIRED, 'New password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->em->getRepository('MhPageBundle:User')->findOneByEmail($input->getArgument('email'));

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $input->getArgument('password')
            )
        );

        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('Password has been changed for '.$user->getEmail());
    }
}

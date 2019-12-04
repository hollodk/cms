<?php

namespace Mh\PageBundle\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PromoteCommand extends Command
{
    protected static $defaultName = 'app:promote';

    private $container;
    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.default_entity_manager');

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'Email to promot to admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->em->getRepository('MhPageBundle:User')->findOneByEmail($input->getArgument('email'));

        $roles = [
            'ROLE_ADMIN',
        ];
        $user->setRoles($roles);

        $this->em->flush();

        $io = new SymfonyStyle($input, $output);

        $io->success('User '.$user->getEmail().' has been promoted to admin');

        return 0;
    }
}

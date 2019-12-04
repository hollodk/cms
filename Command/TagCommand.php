<?php
namespace Mh\PageBundle\Command;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TagCommand extends Command
{
    protected static $defaultName = 'app:tag';

    private $container;
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Process tags')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $tags = $em->getRepository('MhPageBundle:Tag')->findAll();

        foreach ($tags as $tag) {
            $tag->setAmountProducts(count($tag->getProducts()));
            $tag->setAmountPosts(count($tag->getPosts()));

            $this->logger->info('found tags for '.$tag->getName());
        }

        $em->flush();

        return 0;
    }
}

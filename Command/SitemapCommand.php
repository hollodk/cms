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

class SitemapCommand extends Command
{
    protected static $defaultName = 'app:sitemap';

    private $sitemapHelper;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a sitemap')
            ->addArgument('hostname', InputArgument::REQUIRED, 'Hostname of sitemap')
            ->addArgument('output', InputArgument::REQUIRED, 'Output folder')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemapHelper = $this->container->get('Mh\PageBundle\Helper\SitemapHelper');

        $hostname = $input->getArgument('hostname');
        if (!preg_match("/^http/", $hostname)) {
            throw new \Exception('hostname has to start with http:// or https://, input: '.$input->getArgument('hostname'));
        }

        $urls = $sitemapHelper->buildUrls();

        $sitemapHelper->write($hostname, $urls, $input->getArgument('output'));
    }
}

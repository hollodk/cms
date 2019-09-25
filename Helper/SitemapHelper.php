<?php

namespace Mh\PageBundle\Helper;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Mh\PageBundle\Entity\Menu;
use Mh\PageBundle\Entity\MenuItem;
use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Site;
use Symfony\Component\HttpFoundation\Request;

class SitemapHelper
{
    private $em;
    private $logger;
    private $container;

    private $list = [];

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->em = $container->get('doctrine.orm.default_entity_manager');
        $this->logger = $logger;
        $this->container = $container;
    }

    public function buildUrls()
    {
        $router = $this->container->get('router');

        $urls = [];

        $pages = [
            'mh_page_main',
        ];

        foreach ($pages as $page) {
            $urls[] = [
                'loc' => $router->generate($page),
                'changefreq' => 'daily',
                'priority' => '0.5',
                'lastmod' => date('Y-m-d'),
            ];
        }

        $menuItems = $this->em->getRepository('MhPageBundle:MenuItem')->findAll();
        foreach ($menuItems as $menuItem) {
            $urls[] = [
                'loc' => $router->generate('mh_page_wildcard', [
                    'page' => $menuItem->getSlug(),
                ]),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $menuItem->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        $keywords = $this->em->getRepository('MhPageBundle:Keyword')->findAll();
        foreach ($keywords as $keyword) {
            $urls[] = [
                'loc' => $router->generate('mh_page_wildcard', [
                    'page' => $keyword->getSlug(),
                ]),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $keyword->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        $posts = $this->em->getRepository('MhPageBundle:Post')->findAll();
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $router->generate('mh_page_main_post', [
                    'id' => $post->getId(),
                    'slug' => $post->getSlug(),
                ]),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $post->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        return $urls;
    }

    public function write($hostname, $urls, $outputFolder)
    {
        $twig = $this->container->get('twig');

        $files = [];

        $chunks = array_chunk($urls, 35000);
        $this->logger->info('found '.count($chunks).' sitemaps');

        foreach ($chunks as $key=>$chunk) {
            $filename = sprintf('%s/sitemap-%d.xml',
                $outputFolder,
                $key
            );

            $files[] = sprintf('sitemap-%d.xml',
                $key
            );

            $body = $twig->render('@MhPage/sitemap/sitemap.xml.twig', [
                'urls' => $chunk,
                'hostname' => $hostname
            ]);

            $this->logger->info('write file '.$filename);
            file_put_contents($filename, $body);
        }

        $body = $twig->render('@MhPage/sitemap/sitemap-index.xml.twig', [
            'files' => $files,
            'hostname' => $hostname
        ]);

        $filename = sprintf('%s/sitemap.xml',
            $outputFolder
        );
        file_put_contents($filename, $body);

        $this->logger->info('write file '.$filename);
    }
}

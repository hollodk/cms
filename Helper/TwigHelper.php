<?php

namespace Mh\PageBundle\Helper;

use Mh\PageBundle\Entity\Menu;
use Mh\PageBundle\Entity\MenuItem;
use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Site;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class TwigHelper
{
    private $em;
    private $container;
    private $main;
    private $topbar;
    private $header;
    private $pagetitle;
    private $sidebar;
    private $footer;
    private $site;
    private $menu;
    private $menuItems;

    private $list = [];

    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine.orm.default_entity_manager');
        $this->container = $container;
    }

    public function setConfig(array $config)
    {
        if (isset($config['main'])) {
            $this->main = $config['main'];
        }

        if (isset($config['topbar'])) {
            $this->topbar = $config['topbar'];
        }

        if (isset($config['header'])) {
            $this->header = $config['header'];
        }

        if (isset($config['pagetitle'])) {
            $this->pagetitle = $config['pagetitle'];
        }

        if (isset($config['sidebar'])) {
            $this->sidebar = $config['sidebar'];
        }

        if (isset($config['footer'])) {
            $this->footer = $config['footer'];
        }
    }

    public function getMain()
    {
        if (!$this->main) {
            $this->main = [];
            $this->main['author'] = '';
            $this->main['description'] = '';
        }

        return $this->main;
    }

    public function getTopBar()
    {
        if (!$this->topbar) {
            $this->topbar = [];
            $this->topbar['type'] = '';
        }

        return $this->topbar;
    }

    public function getHeader()
    {
        if (!$this->header) {
            $this->header = [];
            $this->header['type'] = '';
        }

        return $this->header;
    }

    public function getPageTitle()
    {
        if (!$this->pagetitle) {
            $this->pagetitle = [];
        }

        return $this->pagetitle;
    }

    public function getSidebar()
    {
        if (!$this->sidebar) {
            $this->sidebar = [];
        }

        return $this->sidebar;
    }

    public function getFooter()
    {
        if (!$this->footer) {
            $this->footer = [];
        }

        return $this->footer;
    }

    public function getSite()
    {
        if (!$this->site) {
            $this->site = [
                'title' => '',
            ];
        }

        return $this->site;
    }

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getMenu()
    {
        if (!$this->menu) {
            $this->menu = [];
        }

        return $this->menu;
    }

    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    public function getMenuItems()
    {
        if (!$this->menuItems) {
            $this->menuItems = [];
        }

        return $this->menuItems;
    }

    public function setMenuItems($menuItems)
    {
        $this->menuItems = $menuItems;
    }
}

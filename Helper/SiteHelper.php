<?php

namespace Mh\PageBundle\Helper;

use Mh\PageBundle\Entity\Menu;
use Mh\PageBundle\Entity\MenuItem;
use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Site;

class SiteHelper
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function installSite()
    {
        $sites = $this->em->getRepository('MhPageBundle:Site')->findAll();
        if (count($sites) > 0) {
            throw new \Exception('You cannot install on top of an existing system');
        }

        $attr = $this->getDefaultAttribute();

        $site = new Site();
        $site->setTitle('My New Website');
        $site->setAttribute(json_encode($attr));

        $this->em->persist($site);

        $menu = new Menu();
        $menu->setTitle('my-menu');
        $menu->setSite($site);

        $this->em->persist($menu);

        $page = new Page();
        $page->setHeader('Frontpage');
        $page->setIsFrontpage(true);
        $page->setSite($site);

        $this->em->persist($page);

        $page = new Page();
        $page->setHeader('About us');
        $page->setSite($site);

        $attr = new \StdClass();
        $attr->topbar = new \StdClass();
        $attr->topbar->type = 'transparent';

        $attr->header = new \StdClass();
        $attr->header->transparent = true;

        $attr->pagetitle = new \StdClass();
        $attr->pagetitle->type = 'image';
        $attr->pagetitle->image_url = 'https://images.pexels.com/photos/845451/pexels-photo-845451.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260';

        $page->setAttribute(json_encode($attr));

        $this->em->persist($page);

        $menuItem = new MenuItem();
        $menuItem->setTitle('About us');
        $menuItem->setSlug('about-us');
        $menuItem->setPriority(50);
        $menuItem->setMenu($menu);
        $menuItem->setPage($page);

        $this->em->persist($menuItem);

        $page = new Page();
        $page->setHeader('Services');
        $page->setSite($site);

        $this->em->persist($page);

        $menuItem = new MenuItem();
        $menuItem->setTitle('Services');
        $menuItem->setSlug('services');
        $menuItem->setPriority(50);
        $menuItem->setMenu($menu);
        $menuItem->setPage($page);

        $this->em->persist($menuItem);

        $this->em->flush();
    }

    private function getDefaultAttribute()
    {
        $attr = '
        {
    "show_admin": true,
    "show_home": true,
    "description": "Free information about betting tips from the best experienced tipsters. Professional analyze made by our betting experts to help you place your online bets.",
    "author": "Betting Kinds",
    "topbar": {
        "type": "light",
        "fullwidth": true,
        "phone": "",
        "email": "info@bettingkings.com",
        "facebook_url": "https://www.facebook.com/groups/197321560306467/"
    },
    "content": {},
    "header": {
        "type": "dark",
        "fullwidth": true,
        "menu_position": "right",
        "transparent": false,
        "responsive_fixed": false,
        "modern": false,
        "mini": false,
        "logo": "left",
        "fixed_disabled": true
    },
    "sidebar": {
        "type": "right",
        "left": false,
        "right": false,
        "modern": true
    },
    "footer": {
        "type": "light",
        "content": false,
        "copyright": true,
        "text": "Copyright &copy; 2019, by betting-kings.com"
    }
}
';

        return json_decode($attr);
    }
}

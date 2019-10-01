<?php

namespace Mh\PageBundle\Helper;

use Mh\PageBundle\Entity\Menu;
use Mh\PageBundle\Entity\MenuItem;
use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Site;
use Mh\PageBundle\Helper\TwigHelper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class SiteHelper
{
    private $em;
    private $twig;
    private $logger;
    private $container;

    private $list = [];

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->em = $container->get('doctrine.orm.default_entity_manager');
        $this->twig = $container->get('Mh\PageBundle\Helper\TwigHelper');
        $this->container = $container;

        $this->setAdminItems();
    }

    private function setAdminItems()
    {
        $this->list[100] = [
            'url' => '',
            'name' => 'Admin',
            'admin' => true,
            'items' => [],
        ];

        $this->list[100]['items'][100] = [
            'url' => 'mh_page_site_index',
            'name' => 'Site',
            'admin' => true,
        ];


        $this->list[100]['items'][200] = [
            'url' => 'mh_page_menu_index',
            'name' => 'Menu',
        ];

        $this->list[100]['items'][300] = [
            'url' => 'mh_page_menu_item_index',
            'name' => 'Menu Items',
        ];

        $this->list[100]['items'][400] = [
            'url' => 'mh_page_page_index',
            'name' => 'Page',
        ];

        $this->list[100]['items'][500] = [
            'url' => 'mh_page_post_index',
            'name' => 'Post',
        ];

        $this->list[100]['items'][600] = [
            'url' => 'mh_page_tag_index',
            'name' => 'Tag',
        ];

        $this->list[100]['items'][700] = [
            'url' => 'mh_page_keyword_index',
            'name' => 'Keyword',
        ];

        $this->list[100]['items'][800] = [
            'url' => 'mh_page_site_manage',
            'name' => 'Manage site',
        ];

        $this->list[100]['items'][900] = [
            'url' => 'mh_page_user_index',
            'name' => 'User',
        ];

        $this->list[100]['items'][1000] = [
            'url' => 'mh_page_referral_index',
            'name' => 'Referral',
        ];

        $this->list[100]['items'][1100] = [
            'url' => 'mh_page_product_index',
            'name' => 'Product',
        ];

        $this->list[100]['items'][1200] = [
            'url' => 'mh_page_brand_index',
            'name' => 'Brand',
        ];

        $this->list[100]['items'][1300] = [
            'url' => 'mh_page_seller_index',
            'name' => 'Seller',
        ];

        $this->list[300] = [
            'url' => 'mh_page_app_logout',
            'name' => 'Logout',
        ];

        $this->list[400] = [
            'url' => 'mh_page_main',
            'target' => '_blank',
            'name' => 'Frontpage',
        ];
    }

    public function addAdminItem($item, $key=null)
    {
        if ($key) {
            $this->list[$key] = $item;
        } else {
            $this->list[] = $item;
        }
    }

    public function getAdminList()
    {
        ksort($this->list);

        return $this->list;
    }

    private function getSite()
    {
        $site = $this->em->getRepository('MhPageBundle:Site')->findOneBy(
            [],
            ['id' => 'DESC']
        );

        return $site;
    }

    private function getMenu()
    {
        $menu = $this->em->getRepository('MhPageBundle:Menu')->findOneBy(
            [],
            ['id' => 'DESC']
        );

        return $menu;
    }

    public function setDefaults()
    {
        $site = $this->getSite();
        $config = json_decode($site->getAttribute(), true);

        $this->twig->setSite($this->getSite());
        $this->twig->setMenu($this->getMenu());
        $this->twig->setConfig($config);

        $items = $this->em->getRepository('MhPageBundle:MenuItem')->findBy(
            [
                'menu' => $this->getMenu(),
                'isActive' => true,
            ],
            ['priority' => 'ASC']
        );

        $this->twig->setMenuItems($items);
    }

    public function build(Request $request, Page $page)
    {
        $this->twig->setConfig($page->getPageConfig());

        $html = $this->process($request, $page->getContent());

        $params = [];
        $params['page'] = $page;
        $params['content'] = $html;

        return $params;
    }

    public function process(Request $request, $html)
    {
        $em = $this->em;
        $twig = $this->container->get('twig');

        $html = preg_replace_callback(
            '/__template:(.*)__/',
            function($input) use ($twig) {
                return $twig->render($input[1]);
            },
            $html
        );

        $container = $this->container;

        $html = preg_replace_callback(
            '/__page:(.*)__/',
            function($input) use ($container, $em, $request) {
                $page = $em->getRepository('MhPageBundle:Page')->findOneByHeader($input[1]);

                $method = 'headerless';
                $controller = $container->get('Mh\PageBundle\Controller\MainController');

                return $controller->$method($request, $page)->getContent();
            },
            $html
        );

        $html = preg_replace_callback(
            '/__render:(.*)__/',
            function($input) use ($container) {
                try {
                    $o = preg_split("/::/", $input[1]);
                    $method = $o[1];

                    $controller = $container->get($o[0]);

                    if (!method_exists($controller, $method)) {
                        throw new \Exception('Method does not exist, controller: '.$o[1].', method: '.$method);
                    }

                    return $controller->$method()->getContent();

                } catch (\Exception $e) {
                    return 'Error occur while render: '.$input[1].', error: '.$e->getMessage();
                }

            },
            $html
        );

        $html = preg_replace_callback(
            '/__blog_latest:(\d+)__/',
            function($input) use ($em, $twig) {
                $limit = $input[1];

                $posts = $em->getRepository('MhPageBundle:Post')->findBy(
                    [],
                    ['id' => 'DESC'],
                    $limit
                );

                return $twig->render('main/blog.html.twig', [
                    'posts' => $posts,
                    'slice' => 200,
                ]);
            },
            $html
        );

        return $html;
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
        $page->setName('frontpage');
        $page->setHeader('Frontpage');
        $page->setIsFrontpage(true);
        $page->setSite($site);

        $this->em->persist($page);

        $page = new Page();
        $page->setName('keyword');
        $page->setHeader('Keyword');
        $page->setSite($site);

        $this->em->persist($page);

        $page = new Page();
        $page->setName('about-us');
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
        $page->setName('services');
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
    "main": {
        "show_admin": true,
        "show_login": false,
        "show_register": false,
        "show_home": true,
        "create_account": true,
        "description": "Free information about betting tips from the best experienced tipsters. Professional analyze made by our betting experts to help you place your online bets.",
        "author": "Betting Kinds",
        "search": false,
        "google": false,
        "logo-light": false,
        "logo-dark": false
    },
    "topbar": {
        "type": "light",
        "text_color": null,
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
        "text": "Copyright &copy; 2019, by betting-kings.com",
        "shot_terms": false
    }
}
';

        return json_decode($attr);
    }
}

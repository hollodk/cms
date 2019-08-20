<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('MhPageBundle:Page')->findOneByIsFrontpage(true);
        if (!$page) {
            $this->addFlash('error', 'No frontpage found, you need to configure the system');

            return $this->redirectToRoute('mh_page_admin');
        }

        return $this->build($request, $page);
    }

    /**
     * @Route("/post-{id}")
     */
    public function post(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $this->getDefaults();

        $params['config'] = json_decode($params['site']->getAttribute(), true);
        $params['post'] = $post;

        $params['next'] = $em->getRepository('MhPageBundle:Post')->getNext($post);
        $params['prev'] = $em->getRepository('MhPageBundle:Post')->getPrev($post);

        return $this->render('main/post.html.twig', $params);
    }

    /**
     * @Route("/play/page-{id}", name="main_play")
     */
    public function play(Request $request, Page $page)
    {
        return $this->build($request, $page);
    }

    public function headerless(Request $request, Page $page)
    {
        $html = $this->process($request, $page->getContent());

        return new Response($html);
    }

    public function page(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menuItem = $em->getRepository('MhPageBundle:MenuItem')->findOneBySlug($request->get('page'));
        if (!$menuItem) {
            throw new \Exception('page not found');
        }

        $page = $menuItem->getPage();

        return $this->build($request, $page);
    }

    private function build(Request $request, Page $page)
    {
        $params = $this->getDefaults();

        $html = $this->process($request, $page->getContent());

        $params['page'] = $page;
        $params['content'] = $html;
        $params['page'] = $page;
        $params['config'] = $page->getPageConfig();

        return $this->render('@MhPage/main/index.html.twig', $params);
    }

    private function getDefaults()
    {
        $em = $this->getDoctrine()->getManager();
        $params = [];

        $params['site'] = $this->getSite();
        $params['menu'] = $this->getMenu();
        $params['menu_items'] = $em->getRepository('MhPageBundle:MenuItem')->findBy(
            ['menu' => $params['menu']],
            ['priority' => 'ASC']
        );

        return $params;
    }

    private function process(Request $request, $html)
    {
        $em = $this->getDoctrine()->getManager();
        $twig = $this->get('twig');

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
                        throw new \Exception('Method does not exist');
                    }

                    return $controller->$method()->getContent();

                } catch (\Exception $e) {
                    return 'Error occur while render: '.$input[1];
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

    private function getSite()
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('MhPageBundle:Site')->findOneBy(
            [],
            ['id' => 'DESC']
        );

        return $site;
    }

    private function getMenu()
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MhPageBundle:Menu')->findOneBy(
            [],
            ['id' => 'DESC']
        );

        return $menu;
    }
}

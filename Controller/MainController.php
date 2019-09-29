<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Page;
use Mh\PageBundle\Entity\Post;
use Mh\PageBundle\Helper\SiteHelper;
use Mh\PageBundle\Helper\TwigHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, SiteHelper $siteHelper)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('MhPageBundle:Page')->findOneByIsFrontpage(true);
        if (!$page) {
            $this->addFlash('error', 'No frontpage found, you need to configure the system');

            return $this->redirectToRoute('mh_page_admin');
        }

        $params = $siteHelper->build($request, $page);

        return $this->render('@MhPage/main/index.html.twig', $params);
    }

    /**
     * @Route("/terms", name="main_terms")
     */
    public function terms(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $siteHelper = $this->get('Mh\PageBundle\Helper\SiteHelper');

        $page = $em->getRepository('MhPageBundle:Page')->findOneByName('terms');
        $params = $siteHelper->build($request, $page);

        return $this->render('main/index.html.twig', $params);
    }

    /**
     * @Route("/post/{id}-{slug}", name="main_post")
     */
    public function post(Post $post, SiteHelper $siteHelper, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $params = [];
        $params['post'] = $post;
        $params['next'] = $em->getRepository('MhPageBundle:Post')->getNext($post);
        $params['prev'] = $em->getRepository('MhPageBundle:Post')->getPrev($post);

        return $this->render('main/post.html.twig', $params);
    }

    /**
     * @Route("/play/page-{id}", name="main_play")
     */
    public function play(Request $request, Page $page, SiteHelper $siteHelper)
    {
        $params = $siteHelper->build($request, $page);

        return $this->render('@MhPage/main/index.html.twig', $params);
    }

    public function headerless(Request $request, Page $page, SiteHelper $siteHelper)
    {
        $html = $siteHelper->process($request, $page->getContent());

        return new Response($html);
    }

    public function page(Request $request, SiteHelper $siteHelper)
    {
        $em = $this->getDoctrine()->getManager();

        $menuItem = $em->getRepository('MhPageBundle:MenuItem')->findOneBySlug($request->get('page'));
        if (!$menuItem) {

            $keyword = $em->getRepository('MhPageBundle:Keyword')->findOneBySlug($request->get('page'));
            if (!$keyword) {
                throw new \Exception('page not found');
            }

            $page = $em->getRepository('MhPageBundle:Page')->findOneByName('keyword');
            $page->setHeader($keyword->getTitle());

        } else {
            $page = $menuItem->getPage();
        }

        $params = $siteHelper->build($request, $page);

        if (isset($keyword)) {
            $params['keyword'] = $keyword;
        }

        return $this->render('@MhPage/main/index.html.twig', $params);
    }
}

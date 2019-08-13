<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('Mh\PageBundle:Page')->findOneByIsFrontpage(true);

        return $this->build($page);
    }

    public function page(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menuItem = $em->getRepository('Mh\PageBundle:MenuItem')->findOneBySlug($request->get('page'));
        $page = $menuItem->getPage();

        return $this->build($page);
    }

    private function build(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository('Mh\PageBundle:Menu')->findOneBy(
            [],
            ['id' => 'DESC']
        );

        return $this->render('main/index.html.twig', [
            'page' => $page,
            'menu' => $menu,
        ]);

    }
}

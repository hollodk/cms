<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Site;
use Mh\PageBundle\Form\SiteType;
use Mh\PageBundle\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/site")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index", methods={"GET"})
     */
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('@MhPage/site/index.html.twig', [
            'sites' => $siteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/manage", name="site_manage")
     */
    public function manage(Request $request): Response
    {
        return $this->render('@MhPage/site/manage.html.twig');
    }

    /**
     * @Route("/install", name="site_install")
     */
    public function install(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $sites = $em->getRepository('Mh\PageBundle:Site')->findAll();

        if (count($sites) > 0) {
            throw new \Exception('You cannot install on top of an existing system');
        }

        $helper = $this->get('Mh\PageBundle\Helper\SiteHelper');
        $helper->installSite();

        return $this->redirectToRoute('mh_page_main');
    }

    /**
     * @Route("/new", name="site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index');
        }

        return $this->render('@MhPage/site/new.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_show", methods={"GET"})
     */
    public function show(Site $site): Response
    {
        return $this->render('@MhPage/site/show.html.twig', [
            'site' => $site,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_index');
        }

        return $this->render('@MhPage/site/edit.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_index');
    }
}

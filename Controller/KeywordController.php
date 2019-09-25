<?php

namespace Mh\PageBundle\Controller;

use Cocur\Slugify\Slugify;
use Mh\PageBundle\Entity\Keyword;
use Mh\PageBundle\Form\KeywordType;
use Mh\PageBundle\Repository\KeywordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/keyword")
 */
class KeywordController extends AbstractController
{
    /**
     * @Route("/", name="keyword_index", methods={"GET"})
     */
    public function index(KeywordRepository $keywordRepository): Response
    {
        $keywords = $keywordRepository->findBy(
            [],
            ['title' => 'ASC'],
            200
        );

        return $this->render('@MhPage/keyword/index.html.twig', [
            'keywords' => $keywords,
        ]);
    }

    /**
     * @Route("/new", name="keyword_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $keyword = new Keyword();
        $form = $this->createForm(KeywordType::class, $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = new Slugify();
            $keyword->setSlug($slug->slugify($keyword->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($keyword);
            $entityManager->flush();

            return $this->redirectToRoute('mh_page_keyword_index');
        }

        return $this->render('@MhPage/keyword/new.html.twig', [
            'keyword' => $keyword,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="keyword_show", methods={"GET"})
     */
    public function show(Keyword $keyword): Response
    {
        return $this->render('@MhPage/keyword/show.html.twig', [
            'keyword' => $keyword,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="keyword_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Keyword $keyword): Response
    {
        $form = $this->createForm(KeywordType::class, $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = new Slugify();
            $keyword->setSlug($slug->slugify($keyword->getTitle()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mh_page_keyword_index');
        }

        return $this->render('@MhPage/keyword/edit.html.twig', [
            'keyword' => $keyword,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="keyword_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Keyword $keyword): Response
    {
        if ($this->isCsrfTokenValid('delete'.$keyword->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($keyword);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mh_page_keyword_index');
    }
}

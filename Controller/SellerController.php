<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Seller;
use Mh\PageBundle\Form\SellerType;
use Mh\PageBundle\Repository\SellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/seller")
 */
class SellerController extends AbstractController
{
    /**
     * @Route("/", name="seller_index", methods={"GET"})
     */
    public function index(SellerRepository $sellerRepository): Response
    {
        return $this->render('@MhPage/seller/index.html.twig', [
            'sellers' => $sellerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="seller_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $seller = new Seller();
        $form = $this->createForm(SellerType::class, $seller);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seller);
            $entityManager->flush();

            return $this->redirectToRoute('mh_page_seller_index');
        }

        return $this->render('@MhPage/seller/new.html.twig', [
            'seller' => $seller,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="seller_show", methods={"GET"})
     */
    public function show(Seller $seller): Response
    {
        return $this->render('@MhPage/seller/show.html.twig', [
            'seller' => $seller,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="seller_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seller $seller): Response
    {
        $form = $this->createForm(SellerType::class, $seller);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mh_page_seller_index');
        }

        return $this->render('@MhPage/seller/edit.html.twig', [
            'seller' => $seller,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="seller_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seller $seller): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seller->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seller);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mh_page_seller_index');
    }
}

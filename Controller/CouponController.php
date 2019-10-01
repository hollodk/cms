<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Coupon;
use Mh\PageBundle\Form\CouponType;
use Mh\PageBundle\Repository\CouponRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/coupon")
 */
class CouponController extends AbstractController
{
    /**
     * @Route("/", name="coupon_index", methods={"GET"})
     */
    public function index(CouponRepository $couponRepository): Response
    {
        return $this->render('@MhPage/coupon/index.html.twig', [
            'coupons' => $couponRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="coupon_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coupon);
            $entityManager->flush();

            return $this->redirectToRoute('mh_page_coupon_index');
        }

        return $this->render('@MhPage/coupon/new.html.twig', [
            'coupon' => $coupon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coupon_show", methods={"GET"})
     */
    public function show(Coupon $coupon): Response
    {
        return $this->render('@MhPage/coupon/show.html.twig', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="coupon_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Coupon $coupon): Response
    {
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mh_page_coupon_index');
        }

        return $this->render('@MhPage/coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coupon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Coupon $coupon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coupon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coupon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mh_page_coupon_index');
    }
}

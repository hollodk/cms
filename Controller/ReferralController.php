<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Referral;
use Mh\PageBundle\Form\ReferralType;
use Mh\PageBundle\Repository\ReferralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/referral")
 */
class ReferralController extends AbstractController
{
    /**
     * @Route("/", name="referral_index", methods={"GET"})
     */
    public function index(ReferralRepository $referralRepository): Response
    {
        return $this->render('@MhPage/referral/index.html.twig', [
            'referrals' => $referralRepository->findAll(),
        ]);
    }
}

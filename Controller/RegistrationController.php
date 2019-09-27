<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Entity\Referral;
use Mh\PageBundle\Entity\User;
use Mh\PageBundle\Form\RegistrationFormType;
use Mh\PageBundle\Security\AppCustomAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $bytes = random_bytes(5);
            $key = bin2hex($bytes);

            $user->setKeyPublic($key);

            $attr = $request->getSession()->get('referral');
            if ($attr) {
                $owner = $entityManager->getRepository('MhPageBundle:User')->findOneByKeyPublic($attr->key);

                if ($owner) {
                    $referral = new Referral();
                    $referral->setAttribute(json_encode($attr));
                    $referral->setReferral($user);
                    $referral->setUser($owner);

                    $entityManager->persist($referral);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('@MhPage/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Helper\SiteHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ElementController extends AbstractController
{
    public function menu(SiteHelper $siteHelper)
    {
        $adminList = $siteHelper->getAdminList();

        return $this->render('@MhPage/element/menu.html.twig', [
            'admin_list' => $adminList,
        ]);
    }
}

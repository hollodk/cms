<?php

namespace Mh\PageBundle\Controller;

use Mh\PageBundle\Helper\SiteHelper;
use Michelf\Markdown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/readme")
 */
class ReadmeController extends AbstractController
{
    /**
     * @Route("/", name="readme_index")
     */
    public function index()
    {
        $readme = file_get_contents(__DIR__.'/../README.md');

        $html = Markdown::defaultTransform($readme);

        return $this->render('@MhPage/readme/index.html.twig', [
            'html' => $html,
        ]);
    }
}

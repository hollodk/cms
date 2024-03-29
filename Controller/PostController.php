<?php

namespace Mh\PageBundle\Controller;

use Cocur\Slugify\Slugify;
use Mh\PageBundle\Entity\Post;
use Mh\PageBundle\Form\PostType;
use Mh\PageBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     * @Route("/", name="login_home", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(
            [],
            ['id' => 'DESC'],
            100
        );

        return $this->render('@MhPage/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();

        if ($request->get('sample')) {
            $post->setTitle('Standard post with a single image');
            $post->setContent(file_get_contents(__DIR__.'/../Resources/views/post/sample.html.twig'));
            $attr = <<<EOF
{
    "images": [
        "https://images.pexels.com/photos/2773655/pexels-photo-2773655.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
    ]
}
EOF;
            $post->setAttribute($attr);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = new Slugify();
            $post->setSlug($slug->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('mh_page_post_index');
        }

        return $this->render('@MhPage/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('@MhPage/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = new Slugify();
            $post->setSlug($slug->slugify($post->getTitle()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mh_page_post_index');
        }

        return $this->render('@MhPage/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mh_page_post_index');
    }
}

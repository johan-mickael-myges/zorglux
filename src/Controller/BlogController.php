<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Service\Blog\BlogCreatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog', name: 'blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    #[IsGranted('list')]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->getLatestPublicBlogs(),
        ]);
    }

    #[Route('/write', name: 'write', methods: ['GET', 'POST'])]
    #[IsGranted('write')]
    public function write(Request $request, BlogCreatorService $service): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($blog);

            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/write.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/read/{slug}', name: 'read', methods: ['GET'])]
    #[IsGranted('read', 'blog')]
    public function read(Blog $blog): Response
    {
        return $this->render('blog/read.html.twig', [
            'blog' => $blog,
        ]);
    }
}

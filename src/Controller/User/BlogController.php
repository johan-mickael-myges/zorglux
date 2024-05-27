<?php

namespace App\Controller\User;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Service\Blog\BlogCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog', name: 'user_blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/user/index.html.twig', [
            'blogs' => $blogRepository->getLatestPublicBlogs(),
        ]);
    }

    #[Route('/write', name: 'write', methods: ['GET', 'POST'])]
    public function write(Request $request, BlogCreatorService $service): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($blog);

            return $this->redirectToRoute('user_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/user/write.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }
}

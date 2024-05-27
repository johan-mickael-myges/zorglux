<?php

namespace App\Controller\User;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
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

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/user/add.html.twig', [
            'blogs' => $blogRepository->getLatestPublicBlogs(),
        ]);
    }
}

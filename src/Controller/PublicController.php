<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'public_')]
class PublicController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->getLatestPublicBlogs();
        return $this->render('public/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    #[Route('/about', name: 'about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('static/about.html.twig');
    }
}

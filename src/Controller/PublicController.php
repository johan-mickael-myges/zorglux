<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Service\Blog\BlogRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'public_')]
class PublicController extends AbstractController
{
    #[Route(
        '/',
        name: 'index',
        options: [
            'sitemap' => [
                'changefreq' => UrlConcrete::CHANGEFREQ_DAILY,
            ]
        ],
        methods: ['GET']
    )]
    public function index(BlogRepositoryService $blogRepositoryService): Response
    {
        $blog = $blogRepositoryService->getPublicBlog([
            'limit' => 6,
        ]);
        return $this->render('public/index.html.twig', [
            'blogs' => $blog,
        ]);
    }

    #[Route(
        '/about',
        name: 'about',
        options: [
            'sitemap' => [
                'section' => 'public',
                'priority' => 0.8,
                'changefreq' => UrlConcrete::CHANGEFREQ_WEEKLY,
            ]
        ],
        methods: ['GET']
    )]
    public function about(): Response
    {
        return $this->render('static/about.html.twig');
    }
}

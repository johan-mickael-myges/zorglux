<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Service\Blog\BlogRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/feed')]
class RssFeedController extends AbstractController
{
    #[Route('', name: 'rss_feed')]
    public function rssFeed(BlogRepositoryService $service): Response
    {
        /**
         * @var Blog[] $posts
         */
        $posts = $service->getPublicBlog();

        $rssFeed = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss/>');
        $rssFeed->addAttribute('version', '2.0');

        $channel = $rssFeed->addChild('channel');
        $channel->addChild('title', 'Zorglux');
        $channel->addChild('link', 'https://www.zorglux.works');
        $channel->addChild('description', 'Découvrez les derniers articles sur Zorglux, une plateforme dynamique dédiée aux blogueurs et aux créateurs de contenu. Lisez, écrivez et partagez vos idées avec une communauté passionnée.');

        foreach ($posts as $post) {
            $item = $channel->addChild('item');
            $item->addChild('title', $post->getTitle());
            $item->addChild('link', 'https://www.zorglux.works'.$this->generateUrl('blog_read', ['slug' => $post->getSlug()]));
            $item->addChild('description', $post->getDescription());
            $item->addChild('pubDate', $post->getCreatedAt()->format('r'));
        }

        $response = new Response($rssFeed->asXML());
        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
    }
}

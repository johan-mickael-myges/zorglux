<?php

namespace App\EventListener;

use App\Repository\BlogRepository;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BlogRepository $blogRepository)
    {
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerBlogUrls($event->getUrlContainer(), $event->getUrlGenerator());
    }

    public function registerBlogUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $posts = $this->blogRepository->findAll();
        $baseUrl = $router->generate('public_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        foreach ($posts as $post) {
            $blogUrl = new UrlConcrete(
                $router->generate(
                    'blog_read',
                    ['slug' => $post->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                $post->getUpdatedAt(),
                UrlConcrete::CHANGEFREQ_MONTHLY,
                0.8
            );

            $imageUrl = new GoogleImageUrlDecorator($blogUrl);
            $imageUrl->addImage(new GoogleImage(
                $baseUrl . '/images/blog/' . $post->getThumbnail(),
                $post->getTitle(),
                null,
                $post->getTitle(),
            ));
            $urls->addUrl($imageUrl, 'blog_images');
        }
    }
}

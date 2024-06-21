<?php

namespace App\EventListener;

use App\Entity\Blog;
use App\Service\Blog\BlogRepositoryService;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\GoogleNewsUrlDecorator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BlogRepositoryService $blogRepositoryService,
    ) {}

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
        $this->registerNewsUrls($event->getUrlContainer(), $event->getUrlGenerator());
    }

    public function registerBlogUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        /**
         * @var Blog[] $posts posts
         */
        $posts = $this->blogRepositoryService->getPublicBlog();
        foreach ($posts as $post) {
            $blogUrl = new UrlConcrete(
                $router->generate(
                    'blog_read',
                    ['slug' => $post->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                $post->getUpdatedAt(),
                UrlConcrete::CHANGEFREQ_WEEKLY,
                0.8
            );

            $imageUrl = new GoogleImageUrlDecorator($blogUrl);
            $imageUrl->addImage(new GoogleImage(
                $post->getThumbnail(),
                $post->getDescription(),
                null,
                $post->getTitle(),
            ));
            $urls->addUrl($imageUrl, 'blog_images');
        }
    }

    public function registerNewsUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        /**
         * @var Blog[] $newsArticles
         */
        $newsArticles = $this->blogRepositoryService->getPublicBlog();
        foreach ($newsArticles as $article) {
            $newsUrl = new UrlConcrete(
                $router->generate(
                    'blog_read',
                    ['slug' => $article->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                $article->getUpdatedAt(),
                UrlConcrete::CHANGEFREQ_HOURLY,
                1.0
            );

            $newsDecorator = new GoogleNewsUrlDecorator(
                $newsUrl,
                'Actualités sur Zorglux',
                'fr',
                $article->getCreatedAt(),
                $article->getTitle()
            );

            $newsDecorator->setKeywords($this->getNewsKeywords($article));

            $imageUrl = new GoogleImageUrlDecorator($newsDecorator);
            $imageUrl->addImage(new GoogleImage(
                $article->getThumbnail(),
                $article->getDescription(),
                'Paris, France',
                $article->getTitle(),
            ));
            $urls->addUrl($imageUrl, 'news');
        }
    }

    private function getNewsKeywords(Blog $article): array
    {
        $keywords = explode(' ', $article->getTitle());
        $keywords = array_filter($keywords, fn($keyword) => strlen($keyword) > 2);
        return array_values(array_merge($keywords, ['Zorglux', 'Actualités', 'Blog', 'Article', 'News', 'SEO', 'ESGI']));
    }
}

<?php

namespace App\Service\Blog;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BlogCreatorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HtmlSanitizerInterface $htmlSanitizer,
    )
    {
    }

    public function create(Blog $blog): void
    {
        $blog->setContent($this->htmlSanitizer->sanitize($blog->getContent()));
        $blog->setSlug((new AsciiSlugger())->slug($blog->getTitle()) . '-' . uniqid());

        $blog->setCreatedAt(new \DateTimeImmutable());
        $blog->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($blog);
        $this->entityManager->flush();
    }
}

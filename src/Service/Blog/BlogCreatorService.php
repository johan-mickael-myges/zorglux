<?php

namespace App\Service\Blog;

use App\Entity\Blog;
use App\Service\S3\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BlogCreatorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HtmlSanitizerInterface $htmlSanitizer,
        private FileUploaderService $fileUploaderService
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

        $imageName = $this->fileUploaderService
            ->uploadImage($blog->getThumbnailFile(), $blog->getThumbnail())
            ->get('ObjectURL');
        $blog->setThumbnail($imageName);

        $this->entityManager->flush();
    }
}

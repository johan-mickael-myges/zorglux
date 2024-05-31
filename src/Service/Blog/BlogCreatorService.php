<?php

namespace App\Service\Blog;

use App\Entity\Blog;
use App\Service\S3\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BlogCreatorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HtmlSanitizerInterface $htmlSanitizer,
        private FileUploaderService $fileUploaderService,
        private Security $security,
    )
    {
    }

    public function create(Blog $blog): void
    {
        $blog->setAuthor($this->security->getUser());

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

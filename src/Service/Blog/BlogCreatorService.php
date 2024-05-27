<?php

namespace App\Service\Blog;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;

class BlogCreatorService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(Blog $blog): void
    {
        $blog->setCreatedAt(new \DateTimeImmutable());
        $blog->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($blog);
        $this->entityManager->flush();
    }
}

<?php

namespace App\Twig\Components;

use App\Entity\Blog;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class BlogCard
{
    public Blog $blog;

    public array $options;

    public function setBlog(
        Blog $blog,
        array $options = []
    ): void {
        $this->blog = $blog;
        $this->options = $options;
    }

    public function getTitle(): string
    {
        return $this->blog->getTitle();
    }

    public function getDescription(): string
    {
        return $this->blog->getDescription();
    }

    public function getThumbnail(): string
    {
        return $this->blog->getThumbnail();
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->blog->getCreatedAt();
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->blog->getUpdatedAt();
    }

    public function getClasses(): string
    {
        return $this->options['classes'] ?? '';
    }
}

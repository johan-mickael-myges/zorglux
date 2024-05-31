<?php

namespace App\Service\Blog;

use App\Criteria\Blog\BlogWithConfidentiality;
use App\Enum\BlogConfidentiality;
use App\Repository\BlogRepository;

class BlogRepositoryService
{
    public function __construct(
        private readonly BlogRepository $blogRepository
    )
    {
    }

    public function getPublicBlog(): array
    {
        return (new BlogWithConfidentiality(BlogConfidentiality::PUBLIC))
            ->apply($this->blogRepository->getBlogQueryBuilder())
            ->getQuery()
            ->getResult();
    }
}

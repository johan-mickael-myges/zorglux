<?php

namespace App\Twig\Components;

use Symfony\Component\Asset\Packages;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LikeButton
{
    public function __construct(
        public int $likes = 0,
        public bool $liked = false,
        public string $classes = '',
    ) {

    }

    public function getIconType(): string
    {
        return $this->liked ? 'fa-solid' : 'fa-regular';
    }
}

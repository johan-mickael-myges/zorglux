<?php

namespace App\Twig\Components;

use Symfony\Component\Asset\Packages;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class CommentButton
{
    public function __construct(
        public int $comments = 0,
        public bool $commented = false,
    ) {

    }

    public function getIconType(): string
    {
        return $this->commented ? 'fa-solid' : 'fa-regular';
    }
}

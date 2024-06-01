<?php

namespace App\Twig\Components;

use Symfony\Component\Asset\Packages;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsLiveComponent]
class LikeButton
{
    use DefaultActionTrait;


    #[LiveProp]
    public bool $liked = false;
    #[LiveProp]
    public int $likes = 0;

    public function __construct(
        public string $classes = '',
    ) {

    }

    public function getIconType(): string
    {
        return $this->liked ? 'fa-solid text-red-500' : 'fa-regular';
    }

    #[LiveAction]
    public function toggleLike()
    {
        $this->liked = !$this->liked;
        $this->likes += $this->liked ? 1 : -1;
    }
}

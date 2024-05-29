<?php

namespace App\Twig\Components;

use Symfony\Component\Asset\Packages;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Avatar
{
    public function __construct(
        Packages $packages,
        public string $img = 'build/images/default/avatar.jpg',
        public string $name = '',
        public int $size = 25,
        public string $classes = ''
    ) {
        $this->img = $packages->getUrl($img);
    }
}

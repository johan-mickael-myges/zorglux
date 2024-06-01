<?php

namespace App\Twig\Components;

use Symfony\Component\Asset\Packages;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Avatar
{
    public function __construct(
        Packages $packages,
        public string $img = 'https://zorglux-bucket.s3.eu-north-1.amazonaws.com/default/avatar.jpg',
        public string $name = '',
        public int $size = 25,
        public string $classes = '',
        public string $imageClasses = '',
        public string $usernameClasses = '',
    ) {
        $this->img = $packages->getUrl($img);
    }
}

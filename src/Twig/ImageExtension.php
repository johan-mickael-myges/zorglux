<?php

// src/Twig/ImageExtension.php
namespace App\Twig;

use App\Service\ImageResizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    private ImageResizer $imageResizer;

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('resize_image', [$this, 'resizeImage']),
        ];
    }

    public function resizeImage(string $imagePath, int $width, int $height): string
    {
        return $this->imageResizer->resize($imagePath, $width, $height);
    }
}

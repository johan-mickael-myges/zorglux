<?php

namespace App\Service;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Symfony\Component\Asset\Packages;

class ImageResizer
{
    private $imageManager;

    public function __construct(
        private Packages $packages
    ) {
        $this->imageManager = new ImageManager(ImagickDriver::class);
    }

    public function resize(string $imagePath, int $width, int $height): string
    {
        $image = $this->imageManager->read($imagePath);
        $image->resize($width, $height);

        $resizedPath = $this->getResizedImagePath($imagePath, $width, $height);
        $image->save($resizedPath);

        return $resizedPath;
    }

    private function getResizedImagePath(string $imagePath, int $width, int $height): string
    {
        $info = pathinfo($imagePath);
        return $info['dirname'].'/'.$info['filename'].'_'.$width.'x'.$height.'.'.$info['extension'];
    }
}

?>

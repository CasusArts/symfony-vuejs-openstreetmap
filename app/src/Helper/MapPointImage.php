<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Helper\ImageInterface;

class MapPointImage implements ImageInterface
{
    private $name;

    private $image;

    public function __construct(UploadedFile $image)
    {
        $this->name = uniqid() . '.' . $image->getClientOriginalExtension();
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): UploadedFile
    {
        return $this->image;
    }
}
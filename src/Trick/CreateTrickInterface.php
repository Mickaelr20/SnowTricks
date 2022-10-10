<?php

namespace App\Trick;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CreateTrickInterface
{
    public function __invoke(UploadedFile $thumbnailFile, Trick $trick): void;
}

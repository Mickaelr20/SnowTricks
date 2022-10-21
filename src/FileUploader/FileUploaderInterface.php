<?php

namespace App\FileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function __invoke(UploadableInterface $uploadable, ?UploadedFile $thumbnailFile, String $targetDirectory): void;
}

<?php

namespace App\Trick;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface EditTrickInterface
{
	public function __invoke(Trick $trick, ?UploadedFile $thumbnailFile): void;
}

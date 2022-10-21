<?php

namespace App\FileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploader implements FileUploaderInterface
{
	public function __construct(private SluggerInterface $slugger)
	{
	}

	public function __invoke(UploadableInterface $uploadable, ?UploadedFile $uploadedFile, string $targetDirectory): void
	{
		if (!empty($uploadedFile)) {
			$originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFilename);
			$newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

			$uploadedFile->move(
				$targetDirectory,
				$newFilename
			);

			$uploadable->setFilename($newFilename);
		}
	}
}

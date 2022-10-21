<?php

namespace App\Trick;

use App\Entity\Trick;
use App\FileUploader\FileUploaderInterface;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CreateTrick implements CreateTrickInterface
{
	public function __construct(private TrickRepository $repo, private FileUploaderInterface $uploader, private SluggerInterface $slugger, private string $imagesDir, private string $thumbnailsDir)
	{
	}

	public function __invoke(Trick $trick, UploadedFile $thumbnailFile): void
	{
		foreach ($trick->getImages() as $image) {
			$imageFile = $image->getImage();
			$this->uploader->__invoke($image, $imageFile, $this->imagesDir);
		}

		if (!empty($thumbnailFile)) {
			$this->uploader->__invoke($trick, $thumbnailFile, $this->thumbnailsDir);
		}

		$trick->setCreated(new \DateTime());
		$this->repo->add($trick, true);
	}
}

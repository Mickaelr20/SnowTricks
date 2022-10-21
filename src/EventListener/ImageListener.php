<?php

namespace App\EventListener;

use App\Entity\Image;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ImageListener
{
	public function __construct(private string $imagesDirectory)
	{
	}

	public function preRemove(Image $image): void
	{
		$dir = $this->imagesDirectory.'/'.$image->getFilename();
		unlink($dir);
	}

	public function preUpdate(Image $image, PreUpdateEventArgs $preUpdateEventArgs): void
	{
		if ($preUpdateEventArgs->hasChangedField('filename')) {
			$dir = $this->imagesDirectory.'/'.$preUpdateEventArgs->getOldValue('filename');
			unlink($dir);
		}
	}
}

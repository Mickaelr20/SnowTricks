<?php
namespace App\EventListener;

use App\Entity\Image;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ImageListener
{

    function __construct(private string $images_directory){
    }

    public function preRemove(Image $image): void
    {
        $dir = $this->images_directory . "/" . $image->getFilename();
        unlink($dir);
    }

    public function preUpdate(Image $image, PreUpdateEventArgs $preUpdateEventArgs): void
    {

        if ($preUpdateEventArgs->hasChangedField('filename')) {
            $dir = $this->images_directory . "/" . $preUpdateEventArgs->getOldValue("filename");
            unlink($dir);
        }
    }
}
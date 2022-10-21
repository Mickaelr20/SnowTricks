<?php

namespace App\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use App\FileUploader\FileUploaderInterface;

final class EditProfileUser implements EditProfileUserInterface
{
    public function __construct(
        private UserRepository $repo,
        private SluggerInterface $slugger,
        private Security $security,
        private FileUploaderInterface $uploader,
        private string $profilePicturesDirectory
    ) {
    }

    public function __invoke(User $user, ?UploadedFile $profilePictureFile): void
    {
        if (!empty($profilePictureFile)) {
            //Supprimer l'ancienne photo de profile si elle existe
            if (!empty($user->getProfilePictureFilename())) {
                $oldProfilePicture = $this->profilePicturesDirectory . "/" . $user->getProfilePictureFilename();
                if (file_exists($oldProfilePicture) && is_file($oldProfilePicture)) {
                    unlink($oldProfilePicture);
                }
            }

            $this->uploader->__invoke($user, $profilePictureFile, $this->profilePicturesDirectory);
        }

        $this->repo->add($user, true);
    }
}

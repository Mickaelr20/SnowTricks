<?php

namespace App\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface EditProfileUserInterface
{
	public function __invoke(User $user, ?UploadedFile $profilePicture): void;
}

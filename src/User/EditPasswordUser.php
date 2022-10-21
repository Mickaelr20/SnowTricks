<?php

namespace App\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class EditPasswordUser implements EditPasswordUserInterface
{
	public function __construct(
		private UserRepository $repo,
		private UserPasswordHasherInterface $passwordHasher,
	) {
	}

	public function __invoke(User $user, string $newPassword): void
	{
		$newHashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
		$user->setPassword($newHashedPassword);
		$this->repo->add($user, true);
	}
}

<?php

namespace App\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUser implements CreateUserInterface
{
	public function __construct(private UserRepository $repo, private UserPasswordHasherInterface $passwordHasher)
	{
	}

	public function __invoke(User $user): void
	{
		$hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
		$user->setPassword($hashedPassword);
		$this->repo->add($user, true);
	}
}

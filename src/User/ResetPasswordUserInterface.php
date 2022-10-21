<?php

namespace App\User;

use App\Entity\User;

interface ResetPasswordUserInterface
{
	public function __invoke(User $user, string $password): void;
}

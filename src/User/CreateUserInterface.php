<?php

namespace App\User;

use App\Entity\User;

interface CreateUserInterface
{
	public function __invoke(User $user): void;
}

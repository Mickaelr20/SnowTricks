<?php

namespace App\User;

interface ForgotPasswordUserInterface
{
	public function __invoke(string $email): void;
}

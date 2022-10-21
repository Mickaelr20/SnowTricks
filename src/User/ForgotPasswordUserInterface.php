<?php

namespace App\User;

interface ForgotPasswordUserInterface
{
    public function __invoke(String $email): void;
}

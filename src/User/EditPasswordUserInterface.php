<?php

namespace App\User;

use App\Entity\User;

interface EditPasswordUserInterface
{
    public function __invoke(User $user, String $newPassword): void;
}

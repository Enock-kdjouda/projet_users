<?php

namespace App\UseCases\User;

use App\Models\User;

class DeleteUserUseCase
{
    public function execute(User $user): void
    {
        $user->delete();
    }
}
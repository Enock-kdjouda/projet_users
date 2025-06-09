<?php

namespace App\UseCases\User;

use App\Models\User;

class UpdateUserUseCase
{
    public function execute(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        return $user;
    }
}

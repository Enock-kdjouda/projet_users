<?php

namespace App\UseCases\User;

use App\Models\User;

class CreateUserUseCase
{
    public function execute(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }
}

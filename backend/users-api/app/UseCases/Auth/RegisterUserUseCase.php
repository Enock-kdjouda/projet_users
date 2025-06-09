<?php
namespace App\UseCases\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);

        $user->sendEmailVerificationNotification();
        return $user;
    }
}

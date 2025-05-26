<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('AdminPass123!'),
            'role'     => 'admin',
         ]);
          User::create([
            'name'     => 'John Manager',
            'email'    => 'manager@example.com',
            'password' => Hash::make('ManagerPass123!'),
            'role'     => 'user',
        ]);
    }
}

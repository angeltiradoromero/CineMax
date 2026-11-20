<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        
    {
        User::updateOrCreate([
            'name' => 'Administrador',
            'last_name' => 'Del Sistema',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make("123456789"),
            'role' => 'admin',
            'remember_token' => Str::random(60),
        ]);

        User::updateOrCreate([
            'name' => 'Usuario',
            'last_name' => 'Del Cine',
            'email' => 'usuario@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make("987654321"),
            'role' => 'user',
            'remember_token' => Str::random(60),
        ]);

    }
}

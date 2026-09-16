<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrador Geral', 'email' => 'admin@farmacia.test', 'role' => 'admin'],
            ['name' => 'Ana Cumbe', 'email' => 'ana.cumbe@farmacia.test', 'role' => 'funcionario'],
            ['name' => 'Carlos Muianga', 'email' => 'carlos.muianga@farmacia.test', 'role' => 'funcionario'],
            ['name' => 'Beatriz Nhantumbo', 'email' => 'beatriz.nhantumbo@farmacia.test', 'role' => 'funcionario'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
                'email_verified_at' => now(),
            ]);
        }
    }
}

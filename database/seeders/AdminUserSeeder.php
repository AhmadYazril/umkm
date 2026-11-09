<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nucomu.com'],
            [
                'name' => 'Admin Nucomu',
                'email' => 'admin@nucomu.com',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@nucomu.com'],
            [
                'name' => 'Kasir Nucomu',
                'email' => 'kasir@nucomu.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );
    }
}

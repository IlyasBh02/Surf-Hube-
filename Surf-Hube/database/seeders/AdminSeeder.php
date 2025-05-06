<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'prenom' => 'Admin',
            'email' => 'admin@surf-hube.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_approved' => true,
        ]);
    }
} 
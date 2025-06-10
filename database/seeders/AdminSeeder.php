<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'), // Ganti kalau mau
                'role' => 'admin',
                'phone' => '08123456789',
                'address' => 'Jl. Admin No.1',
                'identity_type' => 'KTP',
                'identity_file' => 'ktp_admin.jpg',
                'verified' => true,
            ]
        );
    }
}

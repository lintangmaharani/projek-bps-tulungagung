<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin BPS Pertama (Sesuai Foto)
        User::updateOrCreate(
            ['email' => 'akipbpstulungagung@gmail.com'],
            [
                'name' => 'AKIP BPS Tulungagung',
                'password' => Hash::make('bepees3504'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Admin BPS Kedua (Sesuai Foto)
        User::updateOrCreate(
            ['email' => 'zibps3504@gmail.com'],
            [
                'name' => 'ZI BPS Tulungagung',
                'password' => Hash::make('bepees3504'),
                'role' => 'admin',
            ]
        );
    }
}
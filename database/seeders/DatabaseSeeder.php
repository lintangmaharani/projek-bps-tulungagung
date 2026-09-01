<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin AKIP
        User::create([
            'name'     => 'Admin AKIP',
            'email'    => 'akipbpstulungagung@gmail.com',
            'password' => Hash::make('bepees3504'),
            'role'     => 'admin',
        ]);

        // Admin ZI
        User::create([
            'name'     => 'Admin ZI',
            'email'    => 'zibps3504@gmail.com',
            'password' => Hash::make('bepees3504'),
            'role'     => 'admin',
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_code' => 'U001',
            'user_fullname' => 'Administrator',
            'department' => 'IT',
            'user_password' => Hash::make('password123'), // Password: password123
            'data_status' => true,
        ]);

        User::create([
            'user_code' => 'U002',
            'user_fullname' => 'Jane Smith',
            'department' => 'HR',
            'user_password' => Hash::make('password123'), // Password: password456
            'data_status' => true,
        ]);
    }
}

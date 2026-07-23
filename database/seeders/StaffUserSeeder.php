<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@gloriousacademy.com',
            'password' => Hash::make('password123'),
        ]);
    }
}

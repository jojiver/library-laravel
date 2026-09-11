<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Borrower',
                'password' => Hash::make('password'),
            ],
        );
    }
}
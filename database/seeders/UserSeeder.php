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
        $managerRole = \Spatie\Permission\Models\Role::where('name', 'Manager')->first();
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => "manager{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole($managerRole);
        }

        
        $userRole = \Spatie\Permission\Models\Role::where('name', 'User')->first();
        for ($i = 1; $i <= 7; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole($userRole);
        }
    }
    }



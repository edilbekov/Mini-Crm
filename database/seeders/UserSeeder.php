<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@minicrm.com',
            'phone' => '+998901234567',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $manager1 = User::create([
            'name' => 'Manager One',
            'email' => 'manager1@minicrm.com',
            'phone' => '+998901234568',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $manager1->assignRole('manager');

        $manager2 = User::create([
            'name' => 'Manager Two',
            'email' => 'manager2@minicrm.com',
            'phone' => '+998901234569',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $manager2->assignRole('manager');

        User::factory()
            ->count(3)
            ->create()
            ->each(fn($user) => $user->assignRole('manager'));
    }
}

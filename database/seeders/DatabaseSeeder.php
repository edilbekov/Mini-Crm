<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Create customers
        $customers = Customer::factory()->count(50)->create();

        // Get all managers
        $managers = User::role('manager')->get();

        // Create tickets for customers
        $customers->each(function ($customer) use ($managers) {
            Ticket::factory()
                ->count(rand(1, 4))
                ->create([
                    'customer_id' => $customer->id,
                    'manager_id' => $managers->random()->id,
                ]);
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@minicrm.com / password');
        $this->command->info('Manager: manager1@minicrm.com / password');
    }
}

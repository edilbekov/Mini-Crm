<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(Ticket::getStatuses());

        return [
            'customer_id' => Customer::factory(),
            'manager_id' => User::factory(),
            'subject' => fake()->sentence(6),
            'description' => fake()->paragraph(3),
            'status' => $status,
            'resolved_at' => $status === Ticket::STATUS_RESOLVED ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_NEW,
            'resolved_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_IN_PROGRESS,
            'resolved_at' => null,
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_RESOLVED,
            'resolved_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}

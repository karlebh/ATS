<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'             => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'start_date'         => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date'           => $this->faker->dateTimeBetween('now', '+1 month'),
            'floor_team_status'  => $this->faker->randomElement(['assigned', 'not_assigned']),
        ];
    }
}

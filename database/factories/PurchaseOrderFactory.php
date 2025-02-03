<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => $this->faker->name,
            'client_email' => $this->faker->unique()->safeEmail,
            'client_comapny_name' => $this->faker->company,
            'job_number' => $this->faker->unique()->bothify('JOB-####'),
            'po_number' => rand(11111, 99999),
            'budget' => $this->faker->randomFloat(2, 1000, 50000),
            'progress' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'canceled']),
            'current_team' => $this->faker->company,
            'start_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Constants\JobProgress;
use App\Constants\UserRole;
use App\Models\User;
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
        // $startDate = $this->faker->dateTimeBetween('now', '+1 year');
        $startDate = $this->faker->dateTimeBetween('-10 months', '-1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');

        $status = $this->faker->randomElement([
            JobProgress::IN_QUEUE,
            JobProgress::IN_PROGRESS,
            JobProgress::SECONDARY_OPS,
            JobProgress::COMPLETED,
        ]);

        return [
            'user_id' => \App\Models\User::query()->inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'client_name' => $this->faker->name,
            'client_email' => $this->faker->unique()->safeEmail,
            'client_company_name' => $this->faker->company,
            'po_number' => rand(1000000000, 9999999999),
            'budget' => $this->faker->randomFloat(2, 1000, 50000),
            'progress' => JobProgress::getPercentage($status),
            'status' => $status,
            'current_team' => $this->faker->randomElement([
                UserRole::ADMIN,
                UserRole::FLOOR_TEAM,
            ]),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

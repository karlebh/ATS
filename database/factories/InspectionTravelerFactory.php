<?php

namespace Database\Factories;

use App\Constants\TravelerStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionTraveler>
 */
class InspectionTravelerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            TravelerStatus::CREATED,
            TravelerStatus::PENDING,
            TravelerStatus::OVERDUE,
            TravelerStatus::COMPLETED,
            TravelerStatus::PENDING,
        ]);

        return [
            'user_id' => User::factory(),
            'shop_name' => $this->faker->company,
            'shop_email' => $this->faker->unique()->safeEmail,
            'traveler_number' => $this->faker->numberBetween(1000, 9999),
            'status' => $status,
            'start_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'due_at' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'completed_at' => $status == TravelerStatus::PENDING ? $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d') : null,
            'files' => json_encode([$this->faker->fileExtension, $this->faker->fileExtension]),
        ];
    }
}

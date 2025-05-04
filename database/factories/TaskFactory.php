<?php

namespace Database\Factories;

use App\Constants\JobProgress;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'purchase_order_id' => PurchaseOrder::query()->inRandomOrder()->value('id') ?? PurchaseOrder::factory(),
            'status' => $this->faker->randomElement([
                JobProgress::SECONDARY_OPS,
                JobProgress::IN_QUEUE,
                JobProgress::IN_PROGRESS,
                JobProgress::COMPLETED,
            ]),
            'name' => $this->faker->sentence(3),
            'details' => $this->faker->paragraph(4),
        ];
    }
}

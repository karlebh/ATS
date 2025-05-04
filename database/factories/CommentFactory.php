<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (rand(0, 1)) {
            $class = \App\Models\PurchaseOrder::class;
        } else {
            $class = \App\Models\Task::class;
        }

        return [
            'commentable_id' => $class::query()->inRandomOrder()->value('id') ?? $class::factory(),
            'commentable_type' => $class,
            'parent_id' => (rand(1, 10) <= 5 ? rand(1, 3) : null),
            'user_id' => \App\Models\User::query()->inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'content' => $this->faker->sentence(),
            'files' => json_encode([$this->faker->imageUrl()]),
        ];
    }
}

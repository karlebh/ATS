<?php

namespace Database\Factories;

use App\Constants\JobProgress;
use App\Constants\MaterialStatus;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialInventory>
 */
class MaterialInventoryFactory extends Factory
{
    public function definition(): array
    {
        $orderedAt = $this->faker->optional(0.4)->dateTimeBetween('-1 year', 'now');
        $quantity = $this->faker->numberBetween(0, 100);

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'code' => strtoupper($this->faker->unique()->bothify('MAT-#####')),
            'title' => $this->faker->word(),
            'quantity' => $quantity,
            'description' => $this->faker->sentence(),
            'purchased_at' => $this->faker->optional(0.2)->dateTimeBetween('-1 year', 'now'),
            'finished_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 year', 'now'),
            'ordered_at' => $orderedAt,
        ];
    }
}

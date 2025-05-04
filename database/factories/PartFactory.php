<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => str()->uuid(),
            'purchase_order_id' => PurchaseOrder::query()->inRandomOrder()->value('id') ?? PurchaseOrder::factory(),
            'number' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'finish' => $this->faker->word(),
            'rev' => $this->faker->numberBetween(1, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Router;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialList>
 */
class MaterialListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'router_id' =>
            Router::inRandomOrder()->first()->id ?? Router::factory(),
            'uuid' => str()->uuid(),
            'description' => $this->faker->sentence(4),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'vendor_email' => $this->faker->company,
            'invoice_id' => Invoice::query()->inRandomOrder()->value('id') ?? Invoice::factory(),
        ];
    }
}

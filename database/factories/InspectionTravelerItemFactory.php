<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionTravelerItem>
 */
class InspectionTravelerItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inspection_traveler_id' =>
            \App\Models\InspectionTraveler::query()->inRandomOrder()->value('id') ?? \App\Models\InspectionTraveler::factory(),
            'uuid' => str()->uuid(),
            'part_number' => $this->faker->unique()->word(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence(),
            'finish' => $this->faker->word(),
            'rev' => strtoupper($this->faker->randomLetter()),
            'department' => $this->faker->word(),
            'ht_stress' => $this->faker->word(),
            'ship_out' => $this->faker->dateTime(),
            'shipped' => $this->faker->dateTime(),
            'deburr' => $this->faker->word(),
            'tooling_check' => $this->faker->word(),
            'process_review' => $this->faker->word(),
            'fai_completed' => $this->faker->word(),
        ];
    }
}

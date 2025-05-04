<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionTravelerOperation>
 */
class InspectionTravelerOperationFactory extends Factory
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
            'inspection_traveler_id' =>
            \App\Models\InspectionTraveler::query()->inRandomOrder()->value('id') ?? \App\Models\InspectionTraveler::factory(),
            'outside_ops' => $this->faker->randomNumber(), // Stores the operation code as an integer
            'vendor' => $this->faker->numberBetween(1, 100), // Stores the quantity as vendor, implemented based off the UI.
            'out_by' => $this->faker->dateTime(),
            'back_by' => $this->faker->dateTime(),
        ];
    }
}

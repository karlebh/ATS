<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::query()->inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'code' => rand(00000, 99999),
            'name' => fake()->name,
            'phone' => fake()->phoneNumber,
            'email' => fake()->unique()->safeEmail,
            'country' => fake()->country,
            'city' => fake()->city,

        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    public function definition(): array
    {
        $user_id = User::inRandomOrder()->value('id') ?? User::factory()->create()->id;

        return [
            'user_id' => $user_id,
            'code'  => $this->faker->unique()->bothify('VENDOR#####'),
            'name'  => $this->faker->company,
            'phone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'grand_total' => fake()->randomFloat(2, 1, 99999),
        ];
    }
}

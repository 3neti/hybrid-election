<?php

namespace Database\Factories;

use App\Enums\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->word(),
            'name' => fake()->name(),
            'level' => Level::random()->value,
            'count' => fake()->randomNumber(),
        ];
    }
}

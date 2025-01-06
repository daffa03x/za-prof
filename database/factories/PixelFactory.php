<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pixel>
 */
class PixelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'type' => fake()->randomElement(['Meta', 'Tiktok']),
            'id_event' => fake()->unique()->numberBetween(10),
            'status' => fake()->boolean() ? 1 : 0,
        ];
    }
}

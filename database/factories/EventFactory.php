<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
            'mitra' => fake()->name(),
            'website' => fake()->freeEmailDomain(),
            'status' => fake()->boolean() ? 1 : 0,
            'waktu_mulai' => fake()->dateTime(),
            'waktu_berakhir' => fake()->dateTime(),
            'nama_tempat' => fake()->company(),
            'alamat' => fake()->address(),
            'kota' => fake()->city(),
            'jumlah_tiket' => fake()->numberBetween(1, 20),
            'harga' => fake()->numberBetween(10000, 1000000),
            'deskripsi' => fake()->text(100),
            'image' => fake()->imageUrl(360, 360, 'animals', true),
        ];
        
    }
}

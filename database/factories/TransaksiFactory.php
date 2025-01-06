<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_event' => rand(1, 5),
            'invoice' => fake()->unique()->randomNumber(),
            'jumlah_tiket' => rand(1, 10),
            'total_pembayaran' => rand(100000, 500000),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'telepon' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tanggal_lahir' => fake()->date(),
            'status_pembayaran' => fake()->randomElement(['Success', 'Failed', 'Pending']),
            'tanggal_register' => fake()->dateTime(),
            'tanggal_pembayaran' => fake()->dateTime(),
            'id_payment' => rand(1, 5),
        ];
        
    }
}

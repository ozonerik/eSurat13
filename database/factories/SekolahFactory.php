<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sekolah>
 */
class SekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'npsn' => fake()->unique()->numerify('########'),
            'nss' => fake()->numerify('############'),
            'nama_sekolah' => fake()->company(),
            'visi_sekolah' => fake()->sentence(12),
            'alamat_sekolah' => fake()->address(),
            'kota_kab' => fake()->city(),
            'provinsi' => fake()->state(),
            'kcd_wilayah' => (string) fake()->numberBetween(1, 12),
            'website' => fake()->url(),
            'email' => fake()->safeEmail(),
            'telp' => fake()->phoneNumber(),
            'kodepos' => fake()->postcode(),
            'akreditasi' => fake()->randomElement(['A', 'B', 'C']),
            'logo_sekolah' => null,
            'logo_provinsi' => null,
            'stamp_sekolah' => null,
            'show_stamp' => true,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\KategoriSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriSurat>
 */
class KategoriSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->lexify('KAT-???')),
            'nama' => fake()->unique()->words(2, true),
            'is_active' => true,
        ];
    }
}

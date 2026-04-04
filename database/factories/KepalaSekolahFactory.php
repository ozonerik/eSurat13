<?php

namespace Database\Factories;

use App\Models\KepalaSekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KepalaSekolah>
 */
class KepalaSekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('##################'),
            'nama_kepala_sekolah' => fake()->name(),
            'pangkat_golongan' => null,
            'telp' => null,
            'tanda_tangan' => null,
            'is_active' => true,
        ];
    }
}

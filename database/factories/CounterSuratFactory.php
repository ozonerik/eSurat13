<?php

namespace Database\Factories;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CounterSurat>
 */
class CounterSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_surat_id' => JenisSurat::factory(),
            'kategori_surat_id' => static fn (array $attributes) => JenisSurat::query()->find($attributes['jenis_surat_id'])?->kategori_surat_id
                ?? KategoriSurat::factory()->create()->id,
            'tahun' => (int) now()->format('Y'),
            'last_number' => fake()->numberBetween(0, 200),
        ];
    }
}

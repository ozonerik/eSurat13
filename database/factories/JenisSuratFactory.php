<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisSurat>
 */
class JenisSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->lexify('???')),
            'nama' => fake()->unique()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'template_path' => 'templates/'.fake()->slug().'.docx',
            'requires_approval' => fake()->boolean(90),
            'is_active' => true,
        ];
    }
}

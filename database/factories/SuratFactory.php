<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Surat>
 */
class SuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahun = (int) now()->format('Y');
        $nomor = fake()->unique()->numberBetween(1, 9999);

        return [
            'jenis_surat_id' => JenisSurat::factory(),
            'pembuat_id' => User::factory(),
            'approver_id' => User::factory(),
            'no_surat' => sprintf('%04d/KET/SMKN1-Krangkeng/%d', $nomor, $tahun),
            'perihal' => fake()->sentence(4),
            'tujuan' => fake()->company(),
            'tanggal_surat' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => Surat::STATUS_BOOKED,
            'surat_file_path' => null,
            'verification_token' => null,
            'rejection_note' => null,
            'metadata' => ['source' => 'factory'],
        ];
    }
}

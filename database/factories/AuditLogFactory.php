<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'surat_id' => Surat::factory(),
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['create', 'book_number', 'upload_draft', 'approve', 'reject']),
            'description' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'old_values' => ['status' => 'draft'],
            'new_values' => ['status' => 'menunggu_persetujuan'],
            'logged_at' => now(),
        ];
    }
}

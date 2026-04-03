<?php

namespace Database\Factories;

use App\Models\TelegramLog;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TelegramLog>
 */
class TelegramLogFactory extends Factory
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
            'chat_id' => (string) fake()->numberBetween(100000000, 999999999),
            'message' => 'Nomor surat telah dibooking. Silakan upload draft dalam 24 jam.',
            'status' => fake()->randomElement([
                TelegramLog::STATUS_PENDING,
                TelegramLog::STATUS_SENT,
                TelegramLog::STATUS_FAILED,
            ]),
            'retry_count' => fake()->numberBetween(0, 3),
            'sent_at' => now(),
            'failed_at' => null,
            'response_body' => '{"ok":true}',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSeedMap = [
            'Kepala Sekolah' => 'kepsek',
            'Guru' => 'guru',
            'TU' => 'tu',
            'Kaprog' => 'kaprog',
            'Wakil Kepala Sekolah' => 'wakasek',
            'Pengelola Surat' => 'pengelola',
        ];

        foreach ($roleSeedMap as $roleName => $emailPrefix) {
            $user = User::firstOrCreate(
                ['email' => $emailPrefix.'@test.id'],
                [
                    'name' => fake()->name(),
                    'nip' => fake()->optional()->numerify('##################'),
                    'pangkat_golongan' => null,
                    'telp' => fake()->optional()->phoneNumber(),
                    'tanda_tangan' => null,
                    'is_active' => true,
                    'telegram_chat_id' => null,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ],
            );

            if (! $user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}

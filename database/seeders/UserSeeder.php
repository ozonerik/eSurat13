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
        $user=User::updateOrCreate(
            ['nip' => '197006222000121001'],
            [
                'name' => 'Taufik Rohmanuddin, S.Pd., M.Eng',
                'pangkat_golongan' => 'Pembina Tk.I, IV/b',
                'telp' => null,
                'tanda_tangan' => null,
                'is_active' => true,
                'email' => 'kepsek@test.id',
                'telegram_chat_id' => (string) fake()->unique()->numberBetween(100000000, 999999999),
                'email_verified_at' => now(),
                'password' => Hash::make('kepsek'),
                'remember_token' => Str::random(10),
            ],
        );
        $user->assignRole('Kepala Sekolah');
    }
}

<?php

namespace Database\Seeders;

use App\Models\KepalaSekolah;
use Illuminate\Database\Seeder;

class KepalaSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KepalaSekolah::updateOrCreate(
            ['nip' => '197006222000121001'],
            [
                'nama_kepala_sekolah' => 'Taufik Rohmanuddin, S.Pd., M.Eng',
                'pangkat_golongan' => 'Pembina Tk.I, IV/b',
                'tanda_tangan' => null,
                'is_active' => true,
            ],
        );
    }
}

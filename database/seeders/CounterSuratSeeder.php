<?php

namespace Database\Seeders;

use App\Models\CounterSurat;
use App\Models\KategoriSurat;
use Illuminate\Database\Seeder;

class CounterSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = (int) now()->format('Y');

        $kategoriIds = KategoriSurat::query()
            ->where('is_active', true)
            ->pluck('id');

        foreach ($kategoriIds as $kategoriId) {
            CounterSurat::updateOrCreate(
                [
                    'kategori_surat_id' => $kategoriId,
                    'tahun' => $tahun,
                ],
                [
                    'jenis_surat_id' => null,
                    'last_number' => 0,
                ]
            );
        }
    }
}

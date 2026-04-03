<?php

namespace Database\Seeders;

use App\Models\KategoriSurat;
use Illuminate\Database\Seeder;

class KategoriSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode' => 'AKD', 'nama' => 'Akademik'],
            ['kode' => 'ADM', 'nama' => 'Administrasi'],
            ['kode' => 'KPG', 'nama' => 'Kepegawaian'],
            ['kode' => 'KER', 'nama' => 'Kerja Sama'],
        ];

        foreach ($items as $item) {
            KategoriSurat::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'is_active' => true,
                ]
            );
        }
    }
}

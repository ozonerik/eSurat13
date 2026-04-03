<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'kategori_kode' => 'ADM',
                'kode' => 'KET',
                'nama' => 'Surat Keterangan',
                'deskripsi' => 'Template surat keterangan umum.',
            ],
            [
                'kategori_kode' => 'ADM',
                'kode' => 'UND',
                'nama' => 'Surat Undangan',
                'deskripsi' => 'Template surat undangan resmi.',
            ],
            [
                'kategori_kode' => 'AKD',
                'kode' => 'SK',
                'nama' => 'Surat Keputusan',
                'deskripsi' => 'Template surat keputusan internal sekolah.',
            ],
            [
                'kategori_kode' => 'KPG',
                'kode' => 'TGS',
                'nama' => 'Surat Tugas',
                'deskripsi' => 'Template surat tugas guru/staf.',
            ],
        ];

        foreach ($items as $item) {
            $kategoriId = KategoriSurat::query()
                ->where('kode', $item['kategori_kode'])
                ->value('id');

            JenisSurat::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'kategori_surat_id' => $kategoriId,
                    'nama' => $item['nama'],
                    'deskripsi' => $item['deskripsi'],
                    'template_path' => null,
                    'requires_approval' => true,
                    'is_active' => true,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
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
                'kode' => 'KET',
                'nama' => 'Surat Keterangan',
                'deskripsi' => 'Template surat keterangan umum.',
            ],
            [
                'kode' => 'UND',
                'nama' => 'Surat Undangan',
                'deskripsi' => 'Template surat undangan resmi.',
            ],
            [
                'kode' => 'SK',
                'nama' => 'Surat Keputusan',
                'deskripsi' => 'Template surat keputusan internal sekolah.',
            ],
            [
                'kode' => 'TGS',
                'nama' => 'Surat Tugas',
                'deskripsi' => 'Template surat tugas guru/staf.',
            ],
        ];

        foreach ($items as $item) {
            JenisSurat::updateOrCreate(
                ['kode' => $item['kode']],
                [
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

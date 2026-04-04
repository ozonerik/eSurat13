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
        $kategoriCodes = [
            '421',
            '421.3',
            '421.5',
            '421.6',
            '421.7',
            '422.7',
            '422.6',
            '800',
            '820',
            '830',
            '900',
            '005',
            '045',
            '027',
        ];

        foreach ($kategoriCodes as $kategoriCode) {
            $kategori = KategoriSurat::query()
                ->where('kode', $kategoriCode)
                ->first();

            if (! $kategori) {
                continue;
            }

            $normalizedCode = preg_replace('/[^A-Za-z0-9]/', '', $kategori->kode);

            JenisSurat::updateOrCreate(
                ['kode' => 'JS'.$normalizedCode],
                [
                    'kategori_surat_id' => $kategori->id,
                    'nama' => 'Jenis Surat '.$kategori->nama,
                    'deskripsi' => 'Template default untuk kategori '.$kategori->nama.'.',
                    'template_path' => null,
                    'requires_approval' => true,
                    'is_active' => true,
                ]
            );
        }
    }
}

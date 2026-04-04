<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sekolah::updateOrCreate(
            ['npsn' => '20241355'],
            [
                'nss' => '321021809001',
                'kode_surat' => 'SMKN1-Krangkeng',
                'nama_sekolah' => 'SMKN 1 Krangkeng',
                'visi_sekolah' => 'Mencetak lulusan yang CENDEKIA (Cerdas, Normatif, Dedikatif, Kompeten, Iman dan Taqwa)',
                'alamat_sekolah' => 'Jl. Raya Singakerta Kec. Krangkeng',
                'kota_kab' => 'Kab. Indramayu',
                'provinsi' => 'Jawa Barat',
                'kcd_wilayah' => 'IX',
                'website' => 'http://www.smkn1krangkeng.sch.id',
                'email' => 'admin@smkn1krangkeng.sch.id',
                'telp' => '(0234) 7136113',
                'kodepos' => '45284',
                'akreditasi' => 'A',
                'logo_sekolah' => null,
                'logo_provinsi' => null,
                'stamp_sekolah' => null,
                'show_stamp' => true,
            ],
        );
    }
}

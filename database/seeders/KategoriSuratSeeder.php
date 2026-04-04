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
            ['kode' => '421', 'nama' => 'Urusan Sekolah (Umum)'],
            ['kode' => '421.3', 'nama' => 'Administrasi SMA'],
            ['kode' => '421.5', 'nama' => 'Administrasi SMK'],
            ['kode' => '421.6', 'nama' => 'Kegiatan Sekolah Kenaikan Kelas, Kelulusan, Dies Natalis'],
            ['kode' => '421.7', 'nama' => 'Kegiatan Belajar Mengajar Kurikulum dan Kesiswaan'],
            ['kode' => '422.7', 'nama' => 'Mutasi atau Keterangan Pindah Peserta Didik'],
            ['kode' => '422.6', 'nama' => 'Penghargaan Prestasi dan Sertifikasi Siswa'],
            ['kode' => '800', 'nama' => 'Urusan Kepegawaian Guru dan Tenaga Kependidikan (GTK)'],
            ['kode' => '820', 'nama' => 'Mutasi, Pengangkatan, Perpindahan Tugas Pegawai'],
            ['kode' => '830', 'nama' => 'Kedudukan Hukum Cuti dan Izin Belajar Guru'],
            ['kode' => '900', 'nama' => 'Urusan Keuangan Sekolah Anggaran, Otorisasi, Verifikasi'],
            ['kode' => '005', 'nama' => 'Surat Undangan Rapat Dinas atau Acara Sekolah'],
            ['kode' => '045', 'nama' => 'Urusan Kearsipan Penataan Berkas Aktif dan Inaktif'],
            ['kode' => '027', 'nama' => 'Pengadaan Barang dan Jasa Kontrak dan Inventarisasi'],
        ];

        $defaultCodes = array_column($items, 'kode');

        KategoriSurat::query()
            ->whereNotIn('kode', $defaultCodes)
            ->delete();

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

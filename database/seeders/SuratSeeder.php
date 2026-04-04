<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = JenisSurat::query()->first();
        $pembuat = User::query()->first();
        $approver = User::role('Kepala Sekolah')->first();

        if (! $jenisSurat || ! $pembuat || ! $approver) {
            return;
        }

        Surat::updateOrCreate(
            ['no_surat' => '0001/KET/SMKN1-Krangkeng/'.now()->format('Y')],
            [
                'jenis_surat_id' => $jenisSurat->id,
                'pembuat_id' => $pembuat->id,
                'approver_id' => $approver->id,
                'perihal' => 'Contoh Surat Uji',
                'tanggal_surat' => now()->toDateString(),
                'status' => Surat::STATUS_BOOKED,
                'surat_file_path' => null,
                'verification_token' => null,
                'rejection_note' => null,
                'metadata' => ['source' => 'seeder'],
            ],
        );
    }
}

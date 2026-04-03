<?php

namespace App\Services;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\DB;

class SuratNumberService
{
    /**
     * Generate nomor surat unik per jenis surat dan tahun.
     */
    public function generate(JenisSurat $jenisSurat, ?int $tahun = null): string
    {
        $tahun ??= (int) now()->format('Y');

        return DB::transaction(function () use ($jenisSurat, $tahun): string {
            $counter = CounterSurat::query()
                ->where('jenis_surat_id', $jenisSurat->id)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $counter = CounterSurat::create([
                    'jenis_surat_id' => $jenisSurat->id,
                    'tahun' => $tahun,
                    'last_number' => 0,
                ]);
            }

            $counter->increment('last_number');
            $counter->refresh();

            return sprintf('%04d/%s/SMKN1-Krangkeng/%d', $counter->last_number, $jenisSurat->kode, $tahun);
        });
    }
}

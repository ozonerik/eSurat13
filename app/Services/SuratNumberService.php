<?php

namespace App\Services;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\DB;

class SuratNumberService
{
    /**
     * Generate nomor surat unik per kategori surat dan tahun.
     */
    public function generate(JenisSurat $jenisSurat, ?int $tahun = null): string
    {
        $tahun ??= (int) now()->format('Y');
        $jenisSurat->loadMissing('kategoriSurat');
        $kategoriId = $jenisSurat->kategori_surat_id;

        return DB::transaction(function () use ($jenisSurat, $tahun, $kategoriId): string {
            $counterQuery = CounterSurat::query()
                ->where('tahun', $tahun)
                ->lockForUpdate();

            if ($kategoriId) {
                $counterQuery->where('kategori_surat_id', $kategoriId);
            } else {
                $counterQuery->where('jenis_surat_id', $jenisSurat->id);
            }

            $counter = $counterQuery->first();

            if (! $counter) {
                $counter = CounterSurat::create([
                    'kategori_surat_id' => $kategoriId,
                    'jenis_surat_id' => $jenisSurat->id,
                    'tahun' => $tahun,
                    'last_number' => 0,
                ]);
            }

            $counter->increment('last_number');
            $counter->refresh();

            $kode = $jenisSurat->kategoriSurat?->kode ?? $jenisSurat->kode;

            return sprintf('%04d/%s/SMKN1-Krangkeng/%d', $counter->last_number, $kode, $tahun);
        });
    }
}

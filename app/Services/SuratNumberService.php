<?php

namespace App\Services;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\Sekolah;
use App\Models\Surat;
use Illuminate\Support\Facades\DB;

class SuratNumberService
{
    /**
     * Generate nomor surat unik per kategori surat dan tahun.
     *
     * Prioritas pencarian counter (urut dari atas):
     * 1. Global counter  : kategoriSurat.kode = '000' DAN tahun = null
     *    → semua permintaan dari kategori/tahun apapun menggunakan counter ini.
     * 2. Category-scoped counter tanpa tahun : kategori cocok DAN tahun = null
     *    → permintaan dari kategori tersebut tidak terikat tahun, nomor terus lanjut.
     * 3. Normal counter  : kategori + tahun cocok (perilaku default sebelumnya).
     */
    public function generate(JenisSurat $jenisSurat, ?int $tahun = null): string
    {
        $tahun ??= (int) now()->format('Y');
        $jenisSurat->loadMissing('kategoriSurat');
        $kategoriId = $jenisSurat->kategori_surat_id;

        // Ambil kode surat dari data sekolah; fallback ke nilai default.
        $kodeSurat = Sekolah::query()->value('kode_surat') ?? 'SMKN1-Krangkeng';

        return DB::transaction(function () use ($jenisSurat, $tahun, $kategoriId, $kodeSurat): string {
            $kode = $jenisSurat->kategoriSurat?->kode ?? $jenisSurat->kode;

            // Prioritas tertinggi: pakai nomor yang dilepas dari surat expired.
            $releasedSurat = Surat::query()
                ->where('status', Surat::STATUS_EXPIRED)
                ->where('jenis_surat_id', $jenisSurat->id)
                ->whereNotNull('released_no_surat')
                ->where('released_no_surat', 'like', '%/'.$tahun)
                ->lockForUpdate()
                ->orderBy('id')
                ->first();

            if ($releasedSurat) {
                $releasedNumber = $releasedSurat->released_no_surat;

                $releasedSurat->update([
                    'released_no_surat' => null,
                    'no_surat' => null,
                ]);

                if (filled($releasedNumber)) {
                    return $releasedNumber;
                }
            }

            // ── 1. Global counter (kode '000', tahun null) ────────────────────
            $globalCounter = CounterSurat::query()
                ->whereNull('tahun')
                ->whereHas('kategoriSurat', fn ($q) => $q->where('kode', '000'))
                ->lockForUpdate()
                ->first();

            if ($globalCounter) {
                $globalCounter->increment('last_number');
                $globalCounter->refresh();

                return sprintf('%03d/%s/%s/%d', $globalCounter->last_number, $kode, $kodeSurat, $tahun);
            }

            // ── 2. Category-scoped counter tanpa tahun ────────────────────────
            if ($kategoriId) {
                $yearlessCounter = CounterSurat::query()
                    ->where('kategori_surat_id', $kategoriId)
                    ->whereNull('tahun')
                    ->lockForUpdate()
                    ->first();

                if ($yearlessCounter) {
                    $yearlessCounter->increment('last_number');
                    $yearlessCounter->refresh();

                    return sprintf('%03d/%s/%s/%d', $yearlessCounter->last_number, $kode, $kodeSurat, $tahun);
                }
            }

            // ── 3. Logika normal: counter per kategori surat dan tahun ────────
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

            return sprintf('%03d/%s/%s/%d', $counter->last_number, $kode, $kodeSurat, $tahun);
        });
    }
}

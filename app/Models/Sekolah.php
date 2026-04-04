<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    /** @use HasFactory<\Database\Factories\SekolahFactory> */
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nss',
        'kode_surat',
        'nama_sekolah',
        'visi_sekolah',
        'alamat_sekolah',
        'kota_kab',
        'provinsi',
        'kcd_wilayah',
        'website',
        'email',
        'telp',
        'kodepos',
        'akreditasi',
        'logo_sekolah',
        'logo_provinsi',
        'stamp_sekolah',
        'show_stamp',
    ];

    protected function casts(): array
    {
        return [
            'show_stamp' => 'boolean',
        ];
    }
}

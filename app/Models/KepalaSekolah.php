<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaSekolah extends Model
{
    /** @use HasFactory<\Database\Factories\KepalaSekolahFactory> */
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama_kepala_sekolah',
        'pangkat_golongan',
        'telp',
        'tanda_tangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

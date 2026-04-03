<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSurat extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriSuratFactory> */
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function jenisSurats(): HasMany
    {
        return $this->hasMany(JenisSurat::class);
    }
}

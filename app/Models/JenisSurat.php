<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    /** @use HasFactory<\Database\Factories\JenisSuratFactory> */
    use HasFactory;

    protected $fillable = [
        'kategori_surat_id',
        'kode',
        'nama',
        'deskripsi',
        'template_path',
        'requires_approval',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function counters(): HasMany
    {
        return $this->hasMany(CounterSurat::class);
    }

    public function kategoriSurat(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class);
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }
}

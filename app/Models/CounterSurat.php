<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounterSurat extends Model
{
    /** @use HasFactory<\Database\Factories\CounterSuratFactory> */
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'tahun',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'last_number' => 'integer',
        ];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }
}

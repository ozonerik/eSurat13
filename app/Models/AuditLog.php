<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    /** @use HasFactory<\Database\Factories\AuditLogFactory> */
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'user_id',
        'auditable_type',
        'auditable_id',
        'menu_label',
        'model_label',
        'record_label',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'logged_at' => 'datetime',
        ];
    }

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

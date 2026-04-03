<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surat extends Model
{
    /** @use HasFactory<\Database\Factories\SuratFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_BOOKED = 'booked';
    public const STATUS_MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_EXPIRED = 'expired';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_BOOKED,
        self::STATUS_MENUNGGU_PERSETUJUAN,
        self::STATUS_DISETUJUI,
        self::STATUS_DITOLAK,
        self::STATUS_EXPIRED,
    ];

    protected $fillable = [
        'jenis_surat_id',
        'pembuat_id',
        'approver_id',
        'no_surat',
        'perihal',
        'tujuan',
        'tanggal_surat',
        'status',
        'booked_at',
        'booking_expires_at',
        'draft_uploaded_at',
        'approved_at',
        'rejected_at',
        'draft_file_path',
        'final_file_path',
        'verification_token',
        'rejection_note',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'booked_at' => 'datetime',
            'booking_expires_at' => 'datetime',
            'draft_uploaded_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function telegramLogs(): HasMany
    {
        return $this->hasMany(TelegramLog::class);
    }
}

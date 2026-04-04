<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'surat_file_path',
        'verification_token',
        'rejection_note',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Surat $surat): void {
            $surat->status ??= self::STATUS_DRAFT;
            $surat->tanggal_surat ??= now()->toDateString();
        });

        static::saving(function (Surat $surat): void {
            if ($surat->status === self::STATUS_DISETUJUI) {
                if (blank($surat->verification_token)) {
                    $surat->verification_token = self::generateUniqueVerificationToken($surat->id);
                }

                return;
            }

            if ($surat->status === self::STATUS_DITOLAK) {
                return;
            }

            if (filled($surat->surat_file_path)) {
                $surat->status = self::STATUS_MENUNGGU_PERSETUJUAN;

                return;
            }

            if (! $surat->exists) {
                return;
            }

            $isExpired = $surat->created_at?->copy()->addDay()->isPast() ?? false;
            $surat->status = $isExpired ? self::STATUS_EXPIRED : self::STATUS_BOOKED;
        });

        static::created(function (Surat $surat): void {
            if (blank($surat->surat_file_path) && $surat->status === self::STATUS_DRAFT) {
                $surat->updateQuietly([
                    'status' => self::STATUS_BOOKED,
                ]);
            }
        });
    }

    protected static function generateUniqueVerificationToken(?int $currentId = null): string
    {
        do {
            $token = Str::upper(Str::random(10));

            $exists = static::query()
                ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
                ->where('verification_token', $token)
                ->exists();
        } while ($exists);

        return $token;
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

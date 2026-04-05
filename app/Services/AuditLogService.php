<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Sekolah;
use App\Models\Surat;
use App\Models\TelegramLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuditLogService
{
    /**
     * @var array<int, string>
     */
    private const EXCLUDED_UPDATE_FIELDS = [
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    private const SENSITIVE_FIELDS = [
        'password',
        'remember_token',
        'password_confirmation',
    ];

    public function logCreated(Model $record): void
    {
        if (! $this->shouldLog()) {
            return;
        }

        $this->writeLog(
            record: $record,
            action: 'create',
            description: sprintf('%s dibuat melalui menu %s.', $this->resolveSubjectLabel($record), $this->resolveMenuLabel($record)),
            oldValues: null,
            newValues: $this->sanitizeAttributes($record->getAttributes(), $record),
        );
    }

    public function logUpdated(Model $record): void
    {
        if (! $this->shouldLog()) {
            return;
        }

        $changedKeys = array_values(array_diff(array_keys($record->getChanges()), self::EXCLUDED_UPDATE_FIELDS));

        if ($changedKeys === []) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($changedKeys as $key) {
            $oldValues[$key] = $record->getRawOriginal($key);
            $newValues[$key] = $record->getAttribute($key);
        }

        $this->writeLog(
            record: $record,
            action: 'update',
            description: sprintf(
                '%s diperbarui melalui menu %s: %s.',
                $this->resolveSubjectLabel($record),
                $this->resolveMenuLabel($record),
                implode(', ', $changedKeys),
            ),
            oldValues: $this->sanitizeAttributes($oldValues, $record),
            newValues: $this->sanitizeAttributes($newValues, $record),
        );
    }

    public function logDeleted(Model $record): void
    {
        if (! $this->shouldLog()) {
            return;
        }

        $bulkDeleteSource = request()->attributes->get('audit.bulk_delete_source');

        if ($record instanceof Surat && is_string($bulkDeleteSource)) {
            $this->writeLog(
                record: $record,
                action: 'bulk_delete',
                description: $this->resolveBulkDeleteDescription($record, $bulkDeleteSource),
                oldValues: $this->sanitizeAttributes($record->getOriginal(), $record),
                newValues: null,
            );

            return;
        }

        $this->writeLog(
            record: $record,
            action: 'delete',
            description: sprintf('%s dihapus melalui menu %s.', $this->resolveSubjectLabel($record), $this->resolveMenuLabel($record)),
            oldValues: $this->sanitizeAttributes($record->getOriginal(), $record),
            newValues: null,
        );
    }

    /**
     * @param  array<int, string>  $oldValues
     * @param  array<int, string>  $newValues
     */
    public function logRelationshipChange(Model $record, string $relationshipName, array $oldValues, array $newValues): void
    {
        if (! $this->shouldLog()) {
            return;
        }

        sort($oldValues);
        sort($newValues);

        if ($oldValues === $newValues) {
            return;
        }

        $relationLabel = match ($relationshipName) {
            'roles' => 'role',
            'permissions' => 'permission',
            default => $relationshipName,
        };

        $this->writeLog(
            record: $record,
            action: 'sync_' . $relationshipName,
            description: sprintf(
                '%s memperbarui %s melalui menu %s.',
                $this->resolveSubjectLabel($record),
                $relationLabel,
                $this->resolveMenuLabel($record),
            ),
            oldValues: [$relationshipName => $oldValues],
            newValues: [$relationshipName => $newValues],
        );
    }

    private function shouldLog(): bool
    {
        return Auth::check() && ! app()->runningInConsole();
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function sanitizeAttributes(array $attributes, Model $record): array
    {
        $hidden = method_exists($record, 'getHidden') ? $record->getHidden() : [];
        $excludedKeys = array_unique([...$hidden, ...self::SENSITIVE_FIELDS]);
        $sanitized = Arr::except($attributes, $excludedKeys);

        foreach ($sanitized as $key => $value) {
            $sanitized[$key] = $this->normalizeValue($value);
        }

        return $sanitized;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normalizeValue($item);
            }

            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if (is_object($value)) {
            return method_exists($value, '__toString') ? (string) $value : json_encode($value);
        }

        return $value;
    }

    private function writeLog(Model $record, string $action, string $description, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::create([
            'surat_id' => $this->resolveSuratId($record),
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'logged_at' => now(),
        ]);
    }

    private function resolveSuratId(Model $record): ?int
    {
        if ($record instanceof Surat) {
            return $record->getKey();
        }

        $suratId = $record->getAttribute('surat_id');

        return is_numeric($suratId) ? (int) $suratId : null;
    }

    private function resolveBulkDeleteDescription(Surat $record, string $source): string
    {
        return match ($source) {
            'surat-ditolak' => sprintf('%s dihapus massal melalui menu Surat Ditolak.', $this->resolveSubjectLabel($record)),
            'surat-expired' => sprintf('%s dihapus massal melalui menu Surat Expired.', $this->resolveSubjectLabel($record)),
            default => sprintf('%s dihapus massal melalui menu %s.', $this->resolveSubjectLabel($record), $this->resolveMenuLabel($record)),
        };
    }

    private function resolveMenuLabel(Model $record): string
    {
        $routeName = (string) request()->route()?->getName();

        return match (true) {
            str_contains($routeName, '.draft-surats') => 'Draft Surat',
            str_contains($routeName, '.surat-dikirim') => 'Surat Dikirim',
            str_contains($routeName, '.surat-disetujui') => 'Surat Disetujui',
            str_contains($routeName, '.surat-ditolak') => 'Surat Ditolak',
            str_contains($routeName, '.surat-expired') => 'Surat Expired',
            str_contains($routeName, '.review-surats') => 'Review Surat',
            str_contains($routeName, '.surats.') => 'Surat',
            str_contains($routeName, '.users.') => 'User',
            str_contains($routeName, '.kepala-sekolahs.') => 'Kepala Sekolah',
            str_contains($routeName, '.sekolahs.') => 'Sekolah',
            str_contains($routeName, '.jenis-surats.') => 'Jenis Surat',
            str_contains($routeName, '.kategori-surats.') => 'Kategori Surat',
            str_contains($routeName, '.counter-surats.') => 'Counter Surat',
            str_contains($routeName, '.roles.') => 'Role',
            str_contains($routeName, '.permissions.') => 'Permission',
            str_contains($routeName, '.telegram-logs.') => 'Telegram Log',
            default => $this->resolveModelLabel($record),
        };
    }

    private function resolveModelLabel(Model $record): string
    {
        return match ($record::class) {
            Surat::class => 'Surat',
            User::class => 'User',
            Sekolah::class => 'Sekolah',
            JenisSurat::class => 'Jenis Surat',
            KategoriSurat::class => 'Kategori Surat',
            CounterSurat::class => 'Counter Surat',
            TelegramLog::class => 'Telegram Log',
            Role::class => 'Role',
            Permission::class => 'Permission',
            default => class_basename($record),
        };
    }

    private function resolveSubjectLabel(Model $record): string
    {
        $displayValue = $this->resolveDisplayValue($record);
        $modelLabel = $this->resolveModelLabel($record);

        if ($displayValue === null) {
            return sprintf('%s #%s', $modelLabel, $record->getKey());
        }

        return sprintf('%s "%s"', $modelLabel, $displayValue);
    }

    private function resolveDisplayValue(Model $record): ?string
    {
        foreach (['name', 'nama', 'no_surat', 'kode', 'email', 'npsn'] as $attribute) {
            $value = $record->getAttribute($attribute);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }
}
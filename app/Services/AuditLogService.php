<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Sekolah;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class AuditLogService
{
    public function logModelEvent(
        Model $model,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
    ): void {
        if (Auth::id() === null) {
            return;
        }

        try {
            AuditLog::create([
                'surat_id' => $model instanceof Surat ? $model->getKey() : null,
                'user_id' => Auth::id(),
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
                'menu_label' => $this->resolveMenuLabel(),
                'model_label' => $this->resolveModelLabel($model),
                'record_label' => $this->resolveRecordLabel($model),
                'action' => $action,
                'description' => $description ?? $this->resolveDescription($model, $action, $newValues),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'old_values' => $this->sanitizeValues($oldValues),
                'new_values' => $this->sanitizeValues($newValues),
                'logged_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Failed to write audit log.', [
                'model' => $model::class,
                'model_id' => $model->getKey(),
                'action' => $action,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function resolveModelLabel(Model $model): string
    {
        return match ($model::class) {
            Surat::class => 'Surat',
            User::class => 'User',
            KategoriSurat::class => 'Kategori Surat',
            JenisSurat::class => 'Jenis Surat',
            Sekolah::class => 'Sekolah',
            CounterSurat::class => 'Counter Surat',
            Role::class => 'Role',
            Permission::class => 'Permission',
            default => class_basename($model),
        };
    }

    private function resolveMenuLabel(): ?string
    {
        $routeName = Request::route()?->getName();

        if (! is_string($routeName)) {
            return null;
        }

        return match (true) {
            str_contains($routeName, 'resources.surats.draft-surats') => 'Draft Surat',
            str_contains($routeName, 'resources.surats.surat-dikirim') => 'Surat Dikirim',
            str_contains($routeName, 'resources.surats.surat-disetujui') => 'Surat Disetujui',
            str_contains($routeName, 'resources.surats.surat-ditolak') => 'Surat Ditolak',
            str_contains($routeName, 'resources.surats.surat-expired') => 'Surat Expired',
            str_contains($routeName, 'resources.surats.review-surats') => 'Review Surat',
            str_contains($routeName, 'resources.surats.') => 'Buat Surat',
            str_contains($routeName, 'resources.users.') => 'User',
            str_contains($routeName, 'resources.roles.') => 'Role',
            str_contains($routeName, 'resources.permissions.') => 'Permission',
            str_contains($routeName, 'resources.kategori-surats.') => 'Kategori Surat',
            str_contains($routeName, 'resources.jenis-surats.') => 'Jenis Surat',
            str_contains($routeName, 'resources.sekolahs.') => 'Sekolah',
            str_contains($routeName, 'resources.kepala-sekolahs.') => 'Kepala Sekolah',
            str_contains($routeName, 'resources.counter-surats.') => 'Counter Surat',
            default => null,
        };
    }

    private function resolveRecordLabel(Model $model): ?string
    {
        return match ($model::class) {
            Surat::class => $model->no_surat ?: $model->perihal,
            User::class => $model->name,
            KategoriSurat::class, JenisSurat::class, Role::class, Permission::class => $model->name ?? $model->nama,
            Sekolah::class => $model->nama_sekolah,
            CounterSurat::class => $this->resolveCounterSuratLabel($model),
            default => method_exists($model, 'getKey') ? (string) $model->getKey() : null,
        };
    }

    private function resolveCounterSuratLabel(CounterSurat $counterSurat): string
    {
        $kategori = $counterSurat->kategoriSurat?->nama;

        if (filled($kategori)) {
            return sprintf('%s - %s', $kategori, (string) $counterSurat->tahun);
        }

        return sprintf('Counter %s', (string) $counterSurat->tahun);
    }

    private function resolveDescription(Model $model, string $action, ?array $newValues): string
    {
        $label = $this->resolveModelLabel($model);

        return match ($action) {
            'create' => sprintf('%s dibuat.', $label),
            'delete' => sprintf('%s dihapus.', $label),
            'bulk_delete' => sprintf('%s dihapus massal.', $label),
            default => $this->resolveUpdateDescription($label, $newValues),
        };
    }

    private function resolveUpdateDescription(string $label, ?array $newValues): string
    {
        $fields = array_keys($newValues ?? []);

        if ($fields === []) {
            return sprintf('%s diperbarui.', $label);
        }

        return sprintf('%s diperbarui: %s.', $label, implode(', ', $fields));
    }

    private function sanitizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach (['password', 'remember_token', 'verification_token'] as $key) {
            if (array_key_exists($key, $values)) {
                $values[$key] = '[REDACTED]';
            }
        }

        return $values;
    }
}
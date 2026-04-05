<?php

namespace App\Observers;

use App\Models\Surat;
use App\Services\AuditLogService;
use App\Services\SuratTelegramNotificationService;

class SuratObserver
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected SuratTelegramNotificationService $telegramNotificationService,
    ) {
    }

    public function created(Surat $surat): void
    {
        $this->auditLogService->logModelEvent(
            $surat,
            'create',
            null,
            $this->exceptTimestamps($surat->getAttributes()),
        );

        if (filled($surat->status)) {
            $this->telegramNotificationService->notifyStatusChangedToPembuat($surat);
        }
    }

    public function updated(Surat $surat): void
    {
        $newValues = $this->exceptTimestamps($surat->getChanges());

        if ($newValues !== []) {
            $oldValues = [];

            foreach (array_keys($newValues) as $attribute) {
                $oldValues[$attribute] = $surat->getOriginal($attribute);
            }

            $this->auditLogService->logModelEvent($surat, 'update', $oldValues, $newValues);
        }

        if (! $surat->wasChanged('status')) {
            return;
        }

        $this->telegramNotificationService->notifyStatusChangedToPembuat(
            $surat,
            (string) $surat->getRawOriginal('status'),
        );
    }

    public function deleted(Surat $surat): void
    {
        $bulkDeleteSource = request()->attributes->get('audit.bulk_delete_source');

        if (is_string($bulkDeleteSource)) {
            $this->auditLogService->logModelEvent(
                $surat,
                'bulk_delete',
                $this->exceptTimestamps($surat->getOriginal()),
                null,
                $this->resolveBulkDeleteDescription($bulkDeleteSource),
            );

            return;
        }

        $this->auditLogService->logModelEvent(
            $surat,
            'delete',
            $this->exceptTimestamps($surat->getOriginal()),
            null,
        );
    }

    private function resolveBulkDeleteDescription(string $source): string
    {
        return match ($source) {
            'surat-ditolak' => 'Surat ditolak dihapus massal.',
            'surat-expired' => 'Surat expired dihapus massal.',
            default => 'Surat dihapus massal.',
        };
    }

    private function exceptTimestamps(array $values): array
    {
        unset($values['created_at'], $values['updated_at']);

        return $values;
    }
}

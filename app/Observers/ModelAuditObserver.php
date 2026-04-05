<?php

namespace App\Observers;

use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Model;

class ModelAuditObserver
{
    public function __construct(
        protected AuditLogService $auditLogService,
    ) {
    }

    public function created(Model $model): void
    {
        $this->auditLogService->logModelEvent(
            $model,
            'create',
            null,
            $this->exceptTimestamps($model->getAttributes()),
        );
    }

    public function updated(Model $model): void
    {
        $newValues = $this->exceptTimestamps($model->getChanges());

        if ($newValues === []) {
            return;
        }

        $oldValues = [];

        foreach (array_keys($newValues) as $attribute) {
            $oldValues[$attribute] = $model->getOriginal($attribute);
        }

        $this->auditLogService->logModelEvent($model, 'update', $oldValues, $newValues);
    }

    public function deleted(Model $model): void
    {
        $this->auditLogService->logModelEvent(
            $model,
            'delete',
            $this->exceptTimestamps($model->getOriginal()),
            null,
        );
    }

    private function exceptTimestamps(array $values): array
    {
        unset($values['created_at'], $values['updated_at']);

        return $values;
    }
}
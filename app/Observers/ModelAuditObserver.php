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
        $this->auditLogService->logCreated($model);
    }

    public function updated(Model $model): void
    {
        $this->auditLogService->logUpdated($model);
    }

    public function deleted(Model $model): void
    {
        $this->auditLogService->logDeleted($model);
    }
}
<?php

namespace App\Filament\Resources\Pages\Concerns;

use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithRelationshipAudit
{
    /**
     * @var array<string, array<int, string>>
     */
    protected array $relationshipAuditSnapshots = [];

    protected function rememberRelationshipAuditState(string $relationshipName, string $displayColumn = 'name'): void
    {
        $record = $this->getRecord();

        if (! $record instanceof Model || ! method_exists($record, $relationshipName)) {
            return;
        }

        $this->relationshipAuditSnapshots[$relationshipName] = $this->getRelationshipAuditValues($record, $relationshipName, $displayColumn);
    }

    protected function auditRelationshipChange(string $relationshipName, string $displayColumn = 'name', ?array $beforeValues = null): void
    {
        $record = $this->getRecord();

        if (! $record instanceof Model || ! method_exists($record, $relationshipName)) {
            return;
        }

        $previousValues = $beforeValues ?? ($this->relationshipAuditSnapshots[$relationshipName] ?? []);
        $currentValues = $this->getRelationshipAuditValues($record, $relationshipName, $displayColumn);

        app(AuditLogService::class)->logRelationshipChange($record, $relationshipName, $previousValues, $currentValues);

        $this->relationshipAuditSnapshots[$relationshipName] = $currentValues;
    }

    /**
     * @return array<int, string>
     */
    private function getRelationshipAuditValues(Model $record, string $relationshipName, string $displayColumn): array
    {
        return $record->{$relationshipName}()
            ->pluck($displayColumn)
            ->map(fn (mixed $value): string => (string) $value)
            ->filter()
            ->sort()
            ->values()
            ->all();
    }
}
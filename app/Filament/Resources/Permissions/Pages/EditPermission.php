<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Pages\Concerns\InteractsWithRelationshipAudit;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;

class EditPermission extends EditRecord
{
    use InteractsWithRelationshipAudit;

    protected static string $resource = PermissionResource::class;

    protected function afterFill(): void
    {
        $this->rememberRelationshipAuditState('roles');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['guard_name'] = 'web';

        return $data;
    }

    protected function afterSave(): void
    {
        $this->auditRelationshipChange('roles');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Pages\Concerns\InteractsWithRelationshipAudit;
use App\Filament\Resources\Permissions\PermissionResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;

class CreatePermission extends CreateRecord
{
    use InteractsWithRelationshipAudit;

    protected static string $resource = PermissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['guard_name'] = 'web';

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->auditRelationshipChange('roles', 'name', []);
    }
}

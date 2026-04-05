<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\Concerns\InteractsWithRelationshipAudit;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;

class EditUser extends EditRecord
{
    use InteractsWithRelationshipAudit;

    protected static string $resource = UserResource::class;

    protected function afterFill(): void
    {
        $this->rememberRelationshipAuditState('roles');
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

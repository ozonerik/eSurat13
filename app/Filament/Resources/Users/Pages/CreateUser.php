<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\Concerns\InteractsWithRelationshipAudit;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;

class CreateUser extends CreateRecord
{
    use InteractsWithRelationshipAudit;

    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $this->auditRelationshipChange('roles', 'name', []);
    }
}

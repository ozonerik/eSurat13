<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;
use Spatie\Permission\Models\Role;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['guard_name'] = 'web';

        /** @var Role $record */
        $record = $this->record;

        if (RoleResource::isProtectedRoleName($record->name)) {
            $data['name'] = $record->name;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function (): bool {
                    /** @var Role $record */
                    $record = $this->record;

                    return ! RoleResource::isProtectedRoleName($record->name);
                }),
        ];
    }
}

<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use Database\Seeders\RolePermissionSeeder;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('reset_permissions')
                ->label('Reset Permissions')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (): void {
                    app(RolePermissionSeeder::class)->run();

                    Notification::make()
                        ->success()
                        ->title('Permission berhasil direset')
                        ->send();

                    $this->resetTable();
                }),
        ];
    }
}

<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Database\Seeders\RolePermissionSeeder;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('reset_roles')
                ->label('Reset Role')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (): void {
                    app(RolePermissionSeeder::class)->run();

                    Notification::make()
                        ->success()
                        ->title('Role berhasil direset')
                        ->send();

                    $this->resetTable();
                }),
        ];
    }
}

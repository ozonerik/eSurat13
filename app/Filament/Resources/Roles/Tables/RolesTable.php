<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->searchable()
                    ->sortable()
                    ->description(fn (object $record): ?string => RoleResource::isProtectedRoleName($record->name)
                        ? 'Role Sistem'
                        : null),
                IconColumn::make('is_system')
                    ->label('Terkunci')
                    ->getStateUsing(fn (object $record): bool => RoleResource::isProtectedRoleName($record->name))
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                TextColumn::make('permissions.name')
                    ->label('Permission')
                    ->badge()
                    ->separator(','),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}

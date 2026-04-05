<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Models\AuditLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('logged_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('menu_label')
                    ->label('Menu')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('model_label')
                    ->label('Data')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('record_label')
                    ->label('Record')
                    ->state(fn (AuditLog $record): ?string => $record->record_label ?: $record->surat?->no_surat)
                    ->searchable(),
                TextColumn::make('action')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(60),
            ])
            ->filters([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\TelegramLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TelegramLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('chat_id')
                    ->searchable(),
                TextColumn::make('surat.no_surat')
                    ->label('No Surat')
                    ->searchable(),
                TextColumn::make('surat.perihal')
                    ->label('Perihal')
                    ->searchable(),
                TextColumn::make('surat.status')
                    ->label('Status Surat')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status Notifikasi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('retry_count')
                    ->label('Retry')
                    ->sortable(),
                TextColumn::make('sent_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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

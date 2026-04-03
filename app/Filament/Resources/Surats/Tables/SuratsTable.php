<?php

namespace App\Filament\Resources\Surats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisSurat.nama')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pembuat.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'booked' => 'warning',
                        'menunggu_persetujuan' => 'info',
                        'disetujui' => 'success',
                        'ditolak', 'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_surat')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

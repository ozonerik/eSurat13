<?php

namespace App\Filament\Resources\Sekolahs\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('npsn')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nss')
                    ->searchable(),
                TextColumn::make('nama_sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kota_kab')
                    ->label('Kota/Kabupaten')
                    ->searchable(),
                TextColumn::make('provinsi')
                    ->searchable(),
                TextColumn::make('akreditasi')
                    ->sortable(),
                IconColumn::make('show_stamp')
                    ->label('Show Stamp')
                    ->boolean(),
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
            ->toolbarActions([]);
    }
}

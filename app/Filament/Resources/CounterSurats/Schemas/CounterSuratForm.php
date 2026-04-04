<?php

namespace App\Filament\Resources\CounterSurats\Schemas;

use App\Models\KategoriSurat;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CounterSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_surat_id')
                    ->label('Kategori Surat')
                    ->relationship('kategoriSurat', 'nama')
                    ->getOptionLabelFromRecordUsing(
                        static fn (KategoriSurat $record): string => "{$record->kode} - {$record->nama}"
                    )
                    ->searchable(['kode', 'nama'])
                    ->preload()
                    ->required(),
                TextInput::make('tahun')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(null)
                    ->dehydrateStateUsing(static fn (mixed $state): ?int => blank($state) ? null : (int) $state)
                    ->nullable(),
                TextInput::make('last_number')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }
}

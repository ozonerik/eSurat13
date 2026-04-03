<?php

namespace App\Filament\Resources\CounterSurats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CounterSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis_surat_id')
                    ->relationship('jenisSurat', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('tahun')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->required(),
                TextInput::make('last_number')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }
}

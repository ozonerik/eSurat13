<?php

namespace App\Filament\Resources\JenisSurats\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JenisSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('template_path')
                    ->maxLength(255),
                Toggle::make('requires_approval')
                    ->label('Perlu Persetujuan')
                    ->default(true),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                Textarea::make('deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}

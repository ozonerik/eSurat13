<?php

namespace App\Filament\Resources\KepalaSekolahs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KepalaSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true),
                TextInput::make('pangkat_golongan')
                    ->label('Pangkat/Golongan')
                    ->maxLength(255),
                TextInput::make('telp')
                    ->label('Telepon')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                FileUpload::make('tanda_tangan')
                    ->label('Tanda Tangan')
                    ->image()
                    ->directory('tanda-tangan')
                    ->openable()
                    ->downloadable(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}

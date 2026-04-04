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
                TextInput::make('nip')
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true),
                TextInput::make('nama_kepala_sekolah')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pangkat_golongan')
                    ->maxLength(255),
                FileUpload::make('tanda_tangan')
                    ->image()
                    ->directory('kepala-sekolah/tanda-tangan'),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}

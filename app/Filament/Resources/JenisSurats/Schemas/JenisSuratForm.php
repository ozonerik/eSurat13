<?php

namespace App\Filament\Resources\JenisSurats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
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
                Select::make('kategori_surat_id')
                    ->relationship('kategoriSurat', 'nama')
                    ->searchable()
                    ->preload(),
                TextInput::make('kode')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('template_path')
                    ->label('Upload Template')
                    ->directory('templates')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->openable()
                    ->downloadable(),
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

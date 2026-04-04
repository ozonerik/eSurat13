<?php

namespace App\Filament\Resources\Sekolahs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('npsn')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                TextInput::make('nss')
                    ->maxLength(20),
                TextInput::make('kode_surat')
                    ->label('Kode Surat')
                    ->maxLength(50),
                TextInput::make('nama_sekolah')
                    ->required()
                    ->maxLength(255),
                Textarea::make('visi_sekolah')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('alamat_sekolah')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('kota_kab')
                    ->label('Kota/Kabupaten')
                    ->maxLength(255),
                TextInput::make('provinsi')
                    ->maxLength(255),
                TextInput::make('kcd_wilayah')
                    ->label('KCD Wilayah')
                    ->maxLength(20),
                TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('telp')
                    ->label('Telepon')
                    ->maxLength(50),
                TextInput::make('kodepos')
                    ->maxLength(10),
                TextInput::make('akreditasi')
                    ->maxLength(5),
                FileUpload::make('logo_sekolah')
                    ->image()
                    ->directory('sekolah/logo'),
                FileUpload::make('logo_provinsi')
                    ->image()
                    ->directory('sekolah/logo-provinsi'),
                FileUpload::make('stamp_sekolah')
                    ->image()
                    ->directory('sekolah/stamp'),
                Toggle::make('show_stamp')
                    ->label('Tampilkan Stamp')
                    ->default(true),
            ]);
    }
}

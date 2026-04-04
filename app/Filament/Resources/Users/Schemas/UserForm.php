<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
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
                TextInput::make('telegram_chat_id')
                    ->label('Telegram Chat ID')
                    ->maxLength(32)
                    ->unique(ignoreRecord: true),
                FileUpload::make('tanda_tangan')
                    ->label('Tanda Tangan')
                    ->image()
                    ->directory('tanda-tangan')
                    ->openable()
                    ->downloadable(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->minLength(8)
                    ->required(static fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(static fn (?string $state): bool => filled($state)),
                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->revealable()
                    ->required(static fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(false),
                Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}

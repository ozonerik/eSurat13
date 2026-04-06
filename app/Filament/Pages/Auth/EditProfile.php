<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public static function getLabel(): string
    {
        return 'Update Profile';
    }

    public function getTitle(): string
    {
        return static::getLabel();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->live(debounce: 500),
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
                TextInput::make('telegram_chat_id')
                    ->label('Telegram Chat ID')
                    ->default(null)
                    ->maxLength(32)
                    ->unique(ignoreRecord: true),
                FileUpload::make('tanda_tangan')
                    ->label('Tanda Tangan')
                    ->image()
                    ->directory('tanda-tangan')
                    ->openable()
                    ->downloadable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}

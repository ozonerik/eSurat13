<?php

namespace App\Filament\Resources\AuditLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('menu_label')
                    ->label('Menu')
                    ->disabled(),
                TextInput::make('model_label')
                    ->label('Data')
                    ->disabled(),
                TextInput::make('record_label')
                    ->label('Record')
                    ->disabled(),
                Select::make('surat_id')
                    ->relationship('surat', 'no_surat')
                    ->searchable()
                    ->preload()
                    ->disabled(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled(),
                TextInput::make('action')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                TextInput::make('ip_address')
                    ->disabled()
                    ->maxLength(45),
                TextInput::make('user_agent')
                    ->disabled(),
                DateTimePicker::make('logged_at')
                    ->required()
                    ->disabled(),
                Textarea::make('description')
                    ->disabled()
                    ->rows(3)
                    ->columnSpanFull(),
                KeyValue::make('old_values')
                    ->disabled()
                    ->helperText('Nilai lama sebelum perubahan.')
                    ->columnSpanFull(),
                KeyValue::make('new_values')
                    ->disabled()
                    ->helperText('Nilai baru setelah perubahan.')
                    ->columnSpanFull(),
            ]);
    }
}

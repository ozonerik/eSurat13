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
                Select::make('surat_id')
                    ->relationship('surat', 'no_surat')
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('action')
                    ->required()
                    ->maxLength(255),
                TextInput::make('ip_address')
                    ->maxLength(45),
                TextInput::make('user_agent'),
                DateTimePicker::make('logged_at')
                    ->required(),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                KeyValue::make('old_values')
                    ->helperText('Nilai lama sebelum perubahan.')
                    ->columnSpanFull(),
                KeyValue::make('new_values')
                    ->helperText('Nilai baru setelah perubahan.')
                    ->columnSpanFull(),
            ]);
    }
}

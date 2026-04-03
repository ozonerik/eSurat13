<?php

namespace App\Filament\Resources\TelegramLogs\Schemas;

use App\Models\TelegramLog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TelegramLogForm
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
                TextInput::make('chat_id')
                    ->required()
                    ->maxLength(32),
                Select::make('status')
                    ->options([
                        TelegramLog::STATUS_PENDING => TelegramLog::STATUS_PENDING,
                        TelegramLog::STATUS_SENT => TelegramLog::STATUS_SENT,
                        TelegramLog::STATUS_FAILED => TelegramLog::STATUS_FAILED,
                    ])
                    ->required(),
                TextInput::make('retry_count')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(3)
                    ->default(0),
                DateTimePicker::make('sent_at'),
                DateTimePicker::make('failed_at'),
                Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('response_body')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}

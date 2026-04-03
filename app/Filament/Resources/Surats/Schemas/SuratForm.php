<?php

namespace App\Filament\Resources\Surats\Schemas;

use App\Models\Surat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis_surat_id')
                    ->relationship('jenisSurat', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('pembuat_id')
                    ->relationship('pembuat', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('approver_id')
                    ->relationship('approver', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('no_surat')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('perihal')
                    ->required()
                    ->maxLength(255),
                TextInput::make('tujuan')
                    ->maxLength(255),
                DatePicker::make('tanggal_surat'),
                Select::make('status')
                    ->options(array_combine(Surat::STATUSES, Surat::STATUSES))
                    ->required(),
                DateTimePicker::make('booked_at'),
                DateTimePicker::make('booking_expires_at'),
                DateTimePicker::make('draft_uploaded_at'),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('rejected_at'),
                TextInput::make('draft_file_path')
                    ->maxLength(255),
                TextInput::make('final_file_path')
                    ->maxLength(255),
                TextInput::make('verification_token')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('rejection_note')
                    ->rows(3)
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->helperText('Opsional: metadata tambahan surat.')
                    ->columnSpanFull(),
            ]);
    }
}

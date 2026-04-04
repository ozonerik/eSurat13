<?php

namespace App\Filament\Resources\TelegramLogs\Pages;

use App\Filament\Resources\TelegramLogs\TelegramLogResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;

class EditTelegramLog extends EditRecord
{
    protected static string $resource = TelegramLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

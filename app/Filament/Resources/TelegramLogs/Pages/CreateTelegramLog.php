<?php

namespace App\Filament\Resources\TelegramLogs\Pages;

use App\Filament\Resources\TelegramLogs\TelegramLogResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;

class CreateTelegramLog extends CreateRecord
{
    protected static string $resource = TelegramLogResource::class;
}

<?php

namespace App\Filament\Resources\TelegramLogs\Pages;

use App\Filament\Resources\TelegramLogs\TelegramLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTelegramLog extends CreateRecord
{
    protected static string $resource = TelegramLogResource::class;
}

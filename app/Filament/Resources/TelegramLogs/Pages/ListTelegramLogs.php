<?php

namespace App\Filament\Resources\TelegramLogs\Pages;

use App\Filament\Resources\TelegramLogs\TelegramLogResource;
use Filament\Resources\Pages\ListRecords;

class ListTelegramLogs extends ListRecords
{
    protected static string $resource = TelegramLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

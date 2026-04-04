<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\EditRecord;

class EditRecordRedirectIndex extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

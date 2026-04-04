<?php

namespace App\Filament\Resources\CounterSurats\Pages;

use App\Filament\Resources\CounterSurats\CounterSuratResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;

class EditCounterSurat extends EditRecord
{
    protected static string $resource = CounterSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

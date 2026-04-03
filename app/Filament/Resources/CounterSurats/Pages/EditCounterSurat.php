<?php

namespace App\Filament\Resources\CounterSurats\Pages;

use App\Filament\Resources\CounterSurats\CounterSuratResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

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

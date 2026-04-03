<?php

namespace App\Filament\Resources\CounterSurats\Pages;

use App\Filament\Resources\CounterSurats\CounterSuratResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCounterSurats extends ListRecords
{
    protected static string $resource = CounterSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

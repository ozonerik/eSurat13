<?php

namespace App\Filament\Resources\KepalaSekolahs\Pages;

use App\Filament\Resources\KepalaSekolahs\KepalaSekolahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKepalaSekolahs extends ListRecords
{
    protected static string $resource = KepalaSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

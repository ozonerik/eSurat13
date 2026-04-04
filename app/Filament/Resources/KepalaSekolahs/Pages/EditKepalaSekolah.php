<?php

namespace App\Filament\Resources\KepalaSekolahs\Pages;

use App\Filament\Resources\KepalaSekolahs\KepalaSekolahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKepalaSekolah extends EditRecord
{
    protected static string $resource = KepalaSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

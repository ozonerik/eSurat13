<?php

namespace App\Filament\Resources\KepalaSekolahs\Pages;

use App\Filament\Resources\KepalaSekolahs\KepalaSekolahResource;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;

class EditKepalaSekolah extends EditRecord
{
    protected static string $resource = KepalaSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

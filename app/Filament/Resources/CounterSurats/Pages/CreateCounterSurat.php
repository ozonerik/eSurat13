<?php

namespace App\Filament\Resources\CounterSurats\Pages;

use App\Filament\Resources\CounterSurats\CounterSuratResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;

class CreateCounterSurat extends CreateRecord
{
    protected static string $resource = CounterSuratResource::class;
}

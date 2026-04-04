<?php

namespace App\Filament\Resources\JenisSurats\Pages;

use App\Filament\Resources\JenisSurats\JenisSuratResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJenisSurat extends CreateRecord
{
    protected static string $resource = JenisSuratResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}

<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

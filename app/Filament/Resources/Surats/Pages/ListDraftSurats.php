<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\Surat;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class ListDraftSurats extends ListRecords
{
    protected static string $resource = SuratResource::class;

    protected ?string $heading = 'Draft Surat';

    protected function getTableQuery(): Builder|Relation|null
    {
        $user = Auth::user();
        $canViewAll = $user instanceof User && $user->hasAnyRole(['Admin', 'Pengelola Surat', 'Kepala Sekolah']);

        return Surat::query()
            ->where('status', Surat::STATUS_BOOKED)
            ->when(! $canViewAll, fn (Builder $q) => $q->where('pembuat_id', Auth::id()));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

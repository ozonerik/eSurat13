<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\Surat;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class ListSuratDikirim extends ListRecords
{
    protected static string $resource = SuratResource::class;

    protected ?string $heading = 'Surat Dikirim';

    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('surat-dikirim') => 'Surat Dikirim',
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return Surat::query()->whereRaw('1 = 0');
        }

        $canViewAll = $user->can('surat.dikirim.read.all');

        return Surat::query()
            ->where('status', Surat::STATUS_MENUNGGU_PERSETUJUAN)
            ->when(! $canViewAll, fn (Builder $q) => $q->where('pembuat_id', Auth::id()));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

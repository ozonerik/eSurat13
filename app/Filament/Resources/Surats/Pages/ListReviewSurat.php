<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\Surat;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class ListReviewSurat extends ListRecords
{
    protected static string $resource = SuratResource::class;

    protected ?string $heading = 'Review Surat';

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        $user = Auth::user();
        abort_unless(
            $user instanceof User
                && ($user->can('surat.review.read.all') || $user->can('surat.review.read.assigned')),
            403,
        );
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $user = Auth::user();
        $query = Surat::query()->where('status', Surat::STATUS_MENUNGGU_PERSETUJUAN);

        if ($user instanceof User && $user->can('surat.review.read.all')) {
            return $query;
        }

        return $query->where('approver_id', Auth::id());
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

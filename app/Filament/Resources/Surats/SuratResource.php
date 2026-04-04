<?php

namespace App\Filament\Resources\Surats;

use App\Filament\Resources\Surats\Pages\CreateSurat;
use App\Filament\Resources\Surats\Pages\EditSurat;
use App\Filament\Resources\Surats\Pages\ListDraftSurats;
use App\Filament\Resources\Surats\Pages\ListReviewSurat;
use App\Filament\Resources\Surats\Pages\ListSuratDikirim;
use App\Filament\Resources\Surats\Pages\ListSuratDisetujui;
use App\Filament\Resources\Surats\Pages\ListSuratDitolak;
use App\Filament\Resources\Surats\Pages\ListSuratExpired;
use App\Filament\Resources\Surats\Pages\ListSurats;
use App\Filament\Resources\Surats\Schemas\SuratForm;
use App\Filament\Resources\Surats\Tables\SuratsTable;
use App\Models\Surat;
use App\Models\User;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

use function Filament\Support\original_request;

class SuratResource extends Resource
{
    protected static ?string $model = Surat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?string $navigationLabel = 'Buat Surat';

    protected static ?string $modelLabel = 'Surat';

    protected static ?string $pluralModelLabel = 'Buat  Surat';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi Surat';

    public static function form(Schema $schema): Schema
    {
        return SuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuratsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'          => ListSurats::route('/'),
            'create'         => CreateSurat::route('/create'),
            'edit'           => EditSurat::route('/{record}/edit'),
            'draft-surats'   => ListDraftSurats::route('/draft'),
            'surat-dikirim'  => ListSuratDikirim::route('/dikirim'),
            'surat-disetujui'=> ListSuratDisetujui::route('/disetujui'),
            'surat-ditolak'  => ListSuratDitolak::route('/ditolak'),
            'surat-expired'  => ListSuratExpired::route('/expired'),
            'review-surats'  => ListReviewSurat::route('/review'),
        ];
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return [];
        }

        $isAdmin = $user instanceof User && $user->hasRole('Admin');
        $isPengelola = $user instanceof User && $user->hasRole('Pengelola Surat');
        $isKepalaSekolah = $user instanceof User && $user->hasRole('Kepala Sekolah');
        $canReadReview = $user->can('surat.review.read');
        $canViewAllSurat = $isAdmin || $isPengelola;
        $userId = $user?->id;

        $scopedCount = function (string $status) use ($canViewAllSurat, $userId): int {
            $query = Surat::query()->where('status', $status);

            if ($canViewAllSurat) {
                return $query->count();
            }

            return $query
                ->where('pembuat_id', $userId)
                ->whereNull("metadata->" . Surat::METADATA_VIEWED_BY_PEMBUAT_STATUSES . "->{$status}")
                ->count();
        };

        $base = static::getRouteBaseName();

        $items = [
            NavigationItem::make('Buat Surat')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedInbox)
                ->sort(1)
                ->url(static::getUrl('create'))
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.create")
                    || original_request()->routeIs("{$base}.edit")),

            NavigationItem::make('Draft Surat')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedDocumentText)
                ->sort(2)
                ->url(static::getUrl('draft-surats'))
                ->badge(($c = $scopedCount(Surat::STATUS_BOOKED)) > 0 ? (string) $c : null, color: 'warning')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.draft-surats")),

            NavigationItem::make('Surat Dikirim')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->sort(3)
                ->url(static::getUrl('surat-dikirim'))
                ->badge(($c = $scopedCount(Surat::STATUS_MENUNGGU_PERSETUJUAN)) > 0 ? (string) $c : null, color: 'info')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-dikirim")),

            NavigationItem::make('Surat Disetujui')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->sort(4)
                ->url(static::getUrl('surat-disetujui'))
                ->badge(($c = $scopedCount(Surat::STATUS_DISETUJUI)) > 0 ? (string) $c : null, color: 'success')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-disetujui")),

            NavigationItem::make('Surat Ditolak')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedXCircle)
                ->sort(5)
                ->url(static::getUrl('surat-ditolak'))
                ->badge(($c = $scopedCount(Surat::STATUS_DITOLAK)) > 0 ? (string) $c : null, color: 'danger')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-ditolak")),

            NavigationItem::make('Surat Expired')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedClock)
                ->sort(6)
                ->url(static::getUrl('surat-expired'))
                ->badge(($c = $scopedCount(Surat::STATUS_EXPIRED)) > 0 ? (string) $c : null, color: 'danger')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-expired")),
        ];

        if ($canReadReview) {
            $reviewCountQuery = Surat::query()->where('status', Surat::STATUS_MENUNGGU_PERSETUJUAN);

            if ($isKepalaSekolah && ! $canViewAllSurat) {
                $reviewCountQuery
                    ->where('approver_id', $userId)
                    ->whereNull('metadata->' . Surat::METADATA_VIEWED_BY_APPROVER_STATUSES . '->' . Surat::STATUS_MENUNGGU_PERSETUJUAN);
            }

            $reviewCount = $reviewCountQuery->count();

            $items[] = NavigationItem::make('Review Surat')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedEye)
                ->sort(7)
                ->url(static::getUrl('review-surats'))
                ->badge($reviewCount > 0 ? (string) $reviewCount : null, color: 'warning')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.review-surats"));
        }

        return $items;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->hasAnyRole([
                'Admin',
                'Kepala Sekolah',
                'Guru',
                'TU',
                'Kaprog',
                'Wakil Kepala Sekolah',
                'Pengelola Surat',
            ]);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('surat.create');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        /** @var Surat $record */
        if ((int) $record->pembuat_id === (int) $user->id) {
            return true;
        }

        if (! $user->can('surat.review.update') || $record->status !== Surat::STATUS_MENUNGGU_PERSETUJUAN) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->hasRole('Kepala Sekolah') && ((int) $record->approver_id === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        /** @var Surat $record */
        if (filled($record->no_surat)) {
            return false;
        }

        if ($user->can('surat.delete.null-number.all')) {
            return true;
        }

        return $user->can('surat.delete.null-number.own')
            && ((int) $record->pembuat_id === (int) $user->id);
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}

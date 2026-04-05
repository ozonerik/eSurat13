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

        $canReadReview = $user->can('surat.review.read.all') || $user->can('surat.review.read.assigned');
        $userId = $user?->id;

        $scopedCount = function (string $status, string $readAllPermission, string $readOwnPermission) use ($user, $userId): int {
            if (! $user->can($readAllPermission) && ! $user->can($readOwnPermission)) {
                return 0;
            }

            $query = Surat::query()->where('status', $status);

            if ($user->can($readAllPermission)) {
                return $query->count();
            }

            return $query
                ->where('pembuat_id', $userId)
                ->whereNull("metadata->" . Surat::METADATA_VIEWED_BY_PEMBUAT_STATUSES . "->{$status}")
                ->count();
        };

        $base = static::getRouteBaseName();
        $source = request()->query('source');

        $items = [
            NavigationItem::make('Buat Surat')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedInbox)
                ->sort(1)
                ->url(static::getUrl('create'))
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.create")
                    || (original_request()->routeIs("{$base}.edit") && blank($source))),

            NavigationItem::make('Draft Surat')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedDocumentText)
                ->sort(2)
                ->url(static::getUrl('draft-surats'))
                ->badge(($c = $scopedCount(Surat::STATUS_BOOKED, 'surat.draft.read.all', 'surat.draft.read.own')) > 0 ? (string) $c : null, color: 'warning')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.draft-surats")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'draft-surats')),

            NavigationItem::make('Surat Dikirim')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->sort(3)
                ->url(static::getUrl('surat-dikirim'))
                ->badge(($c = $scopedCount(Surat::STATUS_MENUNGGU_PERSETUJUAN, 'surat.dikirim.read.all', 'surat.dikirim.read.own')) > 0 ? (string) $c : null, color: 'info')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-dikirim")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'surat-dikirim')),

            NavigationItem::make('Surat Disetujui')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->sort(4)
                ->url(static::getUrl('surat-disetujui'))
                ->badge(($c = $scopedCount(Surat::STATUS_DISETUJUI, 'surat.disetujui.read.all', 'surat.disetujui.read.own')) > 0 ? (string) $c : null, color: 'success')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-disetujui")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'surat-disetujui')),

            NavigationItem::make('Surat Ditolak')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedXCircle)
                ->sort(5)
                ->url(static::getUrl('surat-ditolak'))
                ->badge(($c = $scopedCount(Surat::STATUS_DITOLAK, 'surat.ditolak.read.all', 'surat.ditolak.read.own')) > 0 ? (string) $c : null, color: 'danger')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-ditolak")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'surat-ditolak')),

            NavigationItem::make('Surat Expired')
                ->group('Transaksi Surat')
                ->icon(Heroicon::OutlinedClock)
                ->sort(6)
                ->url(static::getUrl('surat-expired'))
                ->badge(($c = $scopedCount(Surat::STATUS_EXPIRED, 'surat.expired.read.all', 'surat.expired.read.own')) > 0 ? (string) $c : null, color: 'danger')
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.surat-expired")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'surat-expired')),
        ];

        if ($canReadReview) {
            $reviewCountQuery = Surat::query()->where('status', Surat::STATUS_MENUNGGU_PERSETUJUAN);

            if ($user->can('surat.review.read.assigned')) {
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
                ->isActiveWhen(fn (): bool => original_request()->routeIs("{$base}.review-surats")
                    || (original_request()->routeIs("{$base}.edit") && $source === 'review-surats'));
        }

        return $items;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->canAny([
                'surat.create',
                'surat.draft.read.all', 'surat.draft.read.own',
                'surat.dikirim.read.all', 'surat.dikirim.read.own',
                'surat.disetujui.read.all', 'surat.disetujui.read.own',
                'surat.ditolak.read.all', 'surat.ditolak.read.own',
                'surat.expired.read.all', 'surat.expired.read.own',
                'surat.review.read.all', 'surat.review.read.assigned',
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

        if ($record->status !== Surat::STATUS_MENUNGGU_PERSETUJUAN) {
            return false;
        }

        if ($user->can('surat.review.update.all')) {
            return true;
        }

        return $user->can('surat.review.update.assigned')
            && ((int) $record->approver_id === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        /** @var Surat $record */
        if ($record->no_surat !== null) {
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

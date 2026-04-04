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
        $isAdmin = $user instanceof User && $user->hasRole('Admin');
        $isKepalaSekolah = $user instanceof User && $user->hasRole('Kepala Sekolah');
        $userId = $user?->id;

        $scopedCount = function (string $status) use ($isAdmin, $userId): int {
            $query = Surat::query()->where('status', $status);

            if ($isAdmin) {
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

        if ($isKepalaSekolah) {
            $reviewCount = Surat::query()
                ->where('status', Surat::STATUS_MENUNGGU_PERSETUJUAN)
                ->where('approver_id', $userId)
                ->whereNull('metadata->' . Surat::METADATA_VIEWED_BY_APPROVER_STATUSES . '->' . Surat::STATUS_MENUNGGU_PERSETUJUAN)
                ->count();

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
}

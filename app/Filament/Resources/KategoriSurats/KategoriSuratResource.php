<?php

namespace App\Filament\Resources\KategoriSurats;

use App\Filament\Resources\KategoriSurats\Pages\CreateKategoriSurat;
use App\Filament\Resources\KategoriSurats\Pages\EditKategoriSurat;
use App\Filament\Resources\KategoriSurats\Pages\ListKategoriSurats;
use App\Filament\Resources\KategoriSurats\Schemas\KategoriSuratForm;
use App\Filament\Resources\KategoriSurats\Tables\KategoriSuratsTable;
use App\Models\KategoriSurat;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class KategoriSuratResource extends Resource
{
    protected static ?string $model = KategoriSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Kategori Surat';

    protected static ?string $modelLabel = 'Kategori Surat';

    protected static ?string $pluralModelLabel = 'Kategori Surat';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return KategoriSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriSuratsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoriSurats::route('/'),
            'create' => CreateKategoriSurat::route('/create'),
            'edit' => EditKategoriSurat::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kategori-surat.read');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kategori-surat.create');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kategori-surat.update');
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kategori-surat.delete');
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kategori-surat.delete');
    }
}

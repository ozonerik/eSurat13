<?php

namespace App\Filament\Resources\KepalaSekolahs;

use App\Filament\Resources\KepalaSekolahs\Pages\EditKepalaSekolah;
use App\Filament\Resources\KepalaSekolahs\Pages\ListKepalaSekolahs;
use App\Filament\Resources\KepalaSekolahs\Schemas\KepalaSekolahForm;
use App\Filament\Resources\KepalaSekolahs\Tables\KepalaSekolahsTable;
use App\Models\User;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class KepalaSekolahResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Kepala Sekolah';

    protected static ?string $modelLabel = 'Kepala Sekolah';

    protected static ?string $pluralModelLabel = 'Kepala Sekolah';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return KepalaSekolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KepalaSekolahsTable::configure($table);
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
            'index' => ListKepalaSekolahs::route('/'),
            'edit' => EditKepalaSekolah::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', fn (Builder $query): Builder => $query->where('name', 'Kepala Sekolah'));
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kepala-sekolah.read');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('kepala-sekolah.update');
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}

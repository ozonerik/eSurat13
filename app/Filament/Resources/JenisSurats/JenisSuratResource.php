<?php

namespace App\Filament\Resources\JenisSurats;

use App\Filament\Resources\JenisSurats\Pages\CreateJenisSurat;
use App\Filament\Resources\JenisSurats\Pages\EditJenisSurat;
use App\Filament\Resources\JenisSurats\Pages\ListJenisSurats;
use App\Filament\Resources\JenisSurats\Schemas\JenisSuratForm;
use App\Filament\Resources\JenisSurats\Tables\JenisSuratsTable;
use App\Models\JenisSurat;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class JenisSuratResource extends Resource
{
    protected static ?string $model = JenisSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Jenis Surat';

    protected static ?string $modelLabel = 'Jenis Surat';

    protected static ?string $pluralModelLabel = 'Jenis Surat';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return JenisSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisSuratsTable::configure($table);
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
            'index' => ListJenisSurats::route('/'),
            'create' => CreateJenisSurat::route('/create'),
            'edit' => EditJenisSurat::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user instanceof User && $user->hasAnyRole(['Kaprog', 'Wakil Kepala Sekolah']) && ! $user->hasAnyRole(['Admin', 'Pengelola Surat'])) {
            return $query->where('created_by', $user->id);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['Admin', 'Pengelola Surat', 'Kaprog', 'Wakil Kepala Sekolah']);
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->hasAnyRole(['Admin', 'Pengelola Surat'])) {
            return true;
        }

        /** @var JenisSurat $record */
        return $user->hasAnyRole(['Kaprog', 'Wakil Kepala Sekolah'])
            && ((int) $record->created_by === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['Admin', 'Pengelola Surat']);
    }
}

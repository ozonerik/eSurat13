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

        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->can('jenis-surat.read.all')) {
            return $query;
        }

        if ($user->can('jenis-surat.read.own')) {
            return $query->where('created_by', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->canAny(['jenis-surat.read.all', 'jenis-surat.read.own']);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('jenis-surat.create');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->can('jenis-surat.update.all')) {
            return true;
        }

        /** @var JenisSurat $record */
        return $user->can('jenis-surat.update.own')
            && ((int) $record->created_by === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->can('jenis-surat.delete.all')) {
            return true;
        }

        /** @var JenisSurat $record */
        return $user->can('jenis-surat.delete.own')
            && ((int) $record->created_by === (int) $user->id);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('jenis-surat.delete.all');
    }
}

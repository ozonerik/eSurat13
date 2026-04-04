<?php

namespace App\Filament\Resources\KepalaSekolahs;

use App\Filament\Resources\KepalaSekolahs\Pages\CreateKepalaSekolah;
use App\Filament\Resources\KepalaSekolahs\Pages\EditKepalaSekolah;
use App\Filament\Resources\KepalaSekolahs\Pages\ListKepalaSekolahs;
use App\Filament\Resources\KepalaSekolahs\Schemas\KepalaSekolahForm;
use App\Filament\Resources\KepalaSekolahs\Tables\KepalaSekolahsTable;
use App\Models\KepalaSekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KepalaSekolahResource extends Resource
{
    protected static ?string $model = KepalaSekolah::class;

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
            'create' => CreateKepalaSekolah::route('/create'),
            'edit' => EditKepalaSekolah::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\CounterSurats;

use App\Filament\Resources\CounterSurats\Pages\CreateCounterSurat;
use App\Filament\Resources\CounterSurats\Pages\EditCounterSurat;
use App\Filament\Resources\CounterSurats\Pages\ListCounterSurats;
use App\Filament\Resources\CounterSurats\Schemas\CounterSuratForm;
use App\Filament\Resources\CounterSurats\Tables\CounterSuratsTable;
use App\Models\CounterSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CounterSuratResource extends Resource
{
    protected static ?string $model = CounterSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Counter Surat';

    protected static ?string $modelLabel = 'Counter Surat';

    protected static ?string $pluralModelLabel = 'Counter Surat';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return CounterSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CounterSuratsTable::configure($table);
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
            'index' => ListCounterSurats::route('/'),
            'create' => CreateCounterSurat::route('/create'),
            'edit' => EditCounterSurat::route('/{record}/edit'),
        ];
    }
}

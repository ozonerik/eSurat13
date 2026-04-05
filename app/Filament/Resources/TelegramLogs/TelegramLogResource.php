<?php

namespace App\Filament\Resources\TelegramLogs;

use App\Filament\Resources\TelegramLogs\Pages\CreateTelegramLog;
use App\Filament\Resources\TelegramLogs\Pages\EditTelegramLog;
use App\Filament\Resources\TelegramLogs\Pages\ListTelegramLogs;
use App\Filament\Resources\TelegramLogs\Schemas\TelegramLogForm;
use App\Filament\Resources\TelegramLogs\Tables\TelegramLogsTable;
use App\Models\TelegramLog;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class TelegramLogResource extends Resource
{
    protected static ?string $model = TelegramLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?string $navigationLabel = 'Telegram Log';

    protected static ?string $modelLabel = 'Telegram Log';

    protected static ?string $pluralModelLabel = 'Telegram Log';

    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    public static function form(Schema $schema): Schema
    {
        return TelegramLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TelegramLogsTable::configure($table);
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
            'index' => ListTelegramLogs::route('/'),
            'create' => CreateTelegramLog::route('/create'),
            'edit' => EditTelegramLog::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('telegram-log.read');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
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

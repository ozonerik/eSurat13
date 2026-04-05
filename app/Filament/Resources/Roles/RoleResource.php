<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    /**
     * @var array<int, string>
     */
    public const PROTECTED_ROLE_NAMES = [
        'Admin',
        'Kepala Sekolah',
        'Guru',
        'TU',
        'Kaprog',
        'Wakil Kepala Sekolah',
        'Pengelola Surat',
    ];

    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Role';

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Role';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akses';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('role.read');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('role.create');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('role.update');
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->can('role.delete')) {
            return false;
        }

        /** @var Role $record */
        return ! static::isProtectedRoleName($record->name);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->can('role.delete');
    }

    public static function isProtectedRoleName(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return in_array($roleName, static::PROTECTED_ROLE_NAMES, true);
    }
}

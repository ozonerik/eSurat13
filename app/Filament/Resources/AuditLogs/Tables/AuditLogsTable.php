<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Filament\Resources\AuditLogs\AuditLogResource;
use App\Models\AuditLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('logged_at', 'desc')
            ->columns([
                TextColumn::make('logged_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->description(fn (AuditLog $record): ?string => $record->logged_at?->diffForHumans())
                    ->sortable(),
                TextColumn::make('action')
                    ->label('Jenis Aktivitas')
                    ->badge()
                    ->icon(fn (string $state): string => self::resolveActionIcon($state))
                    ->color(fn (string $state): string => self::resolveActionColor($state))
                    ->formatStateUsing(fn (string $state): string => self::resolveActionLabel($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable()
                    ->wrap()
                    ->lineClamp(2)
                    ->description(fn (AuditLog $record): ?string => self::resolveMenuContext($record)),
                TextColumn::make('change_summary')
                    ->label('Ringkasan Perubahan')
                    ->state(fn (AuditLog $record): string => self::summarizeChanges($record))
                    ->wrap()
                    ->lineClamp(2),
                TextColumn::make('surat_reference')
                    ->label('Referensi')
                    ->state(fn (AuditLog $record): string => $record->surat?->no_surat ?? '-')
                    ->description(fn (AuditLog $record): ?string => $record->surat_id ? 'Surat terkait' : null)
                    ->searchable(query: function ($query, string $search) {
                        return $query->orWhereHas('surat', fn ($suratQuery) => $suratQuery->where('no_surat', 'like', "%{$search}%"));
                    }),
                TextColumn::make('user_name')
                    ->label('Pelaku')
                    ->state(fn (AuditLog $record): string => $record->user?->name ?? 'Sistem')
                    ->description(fn (AuditLog $record): ?string => $record->ip_address ?: null)
                    ->searchable(query: function ($query, string $search) {
                        return $query->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
                    }),
            ])
            ->recordUrl(fn (AuditLog $record): string => AuditLogResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label('Jenis Aktivitas')
                    ->options(self::getActionFilterOptions()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    protected static function getActionFilterOptions(): array
    {
        return [
            'create' => 'Create',
            'update' => 'Update Field',
            'sync_roles' => 'Sync Role',
            'sync_permissions' => 'Sync Permission',
            'delete' => 'Delete',
            'bulk_delete' => 'Bulk Delete',
            'booking_expired' => 'Booking Expired',
        ];
    }

    protected static function resolveActionLabel(string $action): string
    {
        return match ($action) {
            'create' => 'Create',
            'update' => 'Update Field',
            'sync_roles' => 'Sync Role',
            'sync_permissions' => 'Sync Permission',
            'delete' => 'Delete',
            'bulk_delete' => 'Bulk Delete',
            'booking_expired' => 'Booking Expired',
            default => str($action)->headline()->toString(),
        };
    }

    protected static function resolveActionColor(string $action): string
    {
        return match ($action) {
            'create' => 'success',
            'update' => 'info',
            'sync_roles', 'sync_permissions' => 'warning',
            'delete', 'bulk_delete' => 'danger',
            'booking_expired' => 'gray',
            default => 'gray',
        };
    }

    protected static function resolveActionIcon(string $action): string
    {
        return match ($action) {
            'create' => 'heroicon-m-plus-circle',
            'update' => 'heroicon-m-pencil-square',
            'sync_roles', 'sync_permissions' => 'heroicon-m-arrow-path',
            'delete', 'bulk_delete' => 'heroicon-m-trash',
            'booking_expired' => 'heroicon-m-clock',
            default => 'heroicon-m-information-circle',
        };
    }

    protected static function resolveMenuContext(AuditLog $record): ?string
    {
        if (blank($record->description)) {
            return null;
        }

        preg_match('/melalui menu (.+?)(?::|\.)/i', $record->description, $matches);

        return $matches[1] ?? null;
    }

    protected static function summarizeChanges(AuditLog $record): string
    {
        $oldValues = is_array($record->old_values) ? $record->old_values : [];
        $newValues = is_array($record->new_values) ? $record->new_values : [];

        return match ($record->action) {
            'create' => self::summarizeKeyList('Field awal', array_keys($newValues)),
            'update' => self::summarizeKeyList('Field berubah', array_values(array_unique(array_merge(array_keys($oldValues), array_keys($newValues))))),
            'sync_roles' => self::summarizeRelationList('Role', $newValues['roles'] ?? []),
            'sync_permissions' => self::summarizeRelationList('Permission', $newValues['permissions'] ?? []),
            'delete', 'bulk_delete' => self::summarizeKeyList('Snapshot akhir', array_keys($oldValues)),
            'booking_expired' => self::summarizeKeyList('Field sistem', array_values(array_unique(array_merge(array_keys($oldValues), array_keys($newValues))))),
            default => '-',
        };
    }

    /**
     * @param  array<int, string>  $keys
     */
    protected static function summarizeKeyList(string $label, array $keys): string
    {
        if ($keys === []) {
            return '-';
        }

        $preview = array_slice($keys, 0, 4);
        $suffix = count($keys) > 4 ? ' +' . (count($keys) - 4) : '';

        return $label . ': ' . implode(', ', $preview) . $suffix;
    }

    /**
     * @param  array<int, string>  $values
     */
    protected static function summarizeRelationList(string $label, array $values): string
    {
        if ($values === []) {
            return $label . ': -';
        }

        $preview = array_slice($values, 0, 3);
        $suffix = count($values) > 3 ? ' +' . (count($values) - 3) : '';

        return $label . ': ' . implode(', ', $preview) . $suffix;
    }
}

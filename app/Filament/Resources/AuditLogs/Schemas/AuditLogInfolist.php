<?php

namespace App\Filament\Resources\AuditLogs\Schemas;

use App\Models\AuditLog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuditLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Aktivitas')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('logged_at')
                            ->label('Waktu')
                            ->dateTime('d M Y H:i:s'),
                        TextEntry::make('action')
                            ->label('Jenis')
                            ->badge()
                            ->icon(fn (string $state): string => self::resolveActionIcon($state))
                            ->color(fn (string $state): string => self::resolveActionColor($state))
                            ->formatStateUsing(fn (string $state): string => self::resolveActionLabel($state)),
                        TextEntry::make('ip_address')
                            ->label('IP Address')
                            ->placeholder('-'),
                    ]),

                Section::make('Pelaku & Objek')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Dilakukan Oleh')
                            ->placeholder('Sistem'),
                        TextEntry::make('surat.no_surat')
                            ->label('Nomor Surat Terkait')
                            ->placeholder('-'),
                        TextEntry::make('user_agent')
                            ->label('Browser / Device')
                            ->placeholder('-')
                            ->limit(80),
                    ]),

                Section::make('Keterangan')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Deskripsi Aktivitas')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),

                Section::make('Detail Perubahan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('Nilai Sebelum')
                                    ->icon('heroicon-o-arrow-left-circle')
                                    ->iconColor('danger')
                                    ->schema(fn (AuditLog $record): array => self::buildValueEntries($record->old_values, 'old')),
                                Section::make('Nilai Sesudah')
                                    ->icon('heroicon-o-arrow-right-circle')
                                    ->iconColor('success')
                                    ->schema(fn (AuditLog $record): array => self::buildValueEntries($record->new_values, 'new')),
                            ]),
                    ])
                    ->visible(fn (AuditLog $record): bool => ! empty($record->old_values) || ! empty($record->new_values)),
            ]);
    }

    /**
     * @param  array<string, mixed>|null  $values
     * @return array<TextEntry>
     */
    private static function buildValueEntries(?array $values, string $side): array
    {
        if (empty($values)) {
            return [
                TextEntry::make("{$side}_empty")
                    ->label('-')
                    ->state('Tidak ada data')
                    ->placeholder('-'),
            ];
        }

        return collect($values)
            ->map(function (mixed $value, string $key) use ($side): TextEntry {
                $label = str($key)->replace('_', ' ')->title()->toString();
                $displayValue = self::formatValue($value);

                return TextEntry::make("{$side}_{$key}")
                    ->label($label)
                    ->state($displayValue)
                    ->placeholder('-');
            })
            ->values()
            ->all();
    }

    private static function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        if (is_array($value)) {
            if ($value === []) {
                return '-';
            }

            return implode(', ', array_map(fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $value));
        }

        return (string) $value;
    }

    private static function resolveActionLabel(string $action): string
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

    private static function resolveActionColor(string $action): string
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

    private static function resolveActionIcon(string $action): string
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
}

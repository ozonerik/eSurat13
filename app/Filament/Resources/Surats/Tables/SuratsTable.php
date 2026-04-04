<?php

namespace App\Filament\Resources\Surats\Tables;

use App\Models\KategoriSurat;
use App\Models\JenisSurat;
use App\Models\Surat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class SuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisSurat.nama')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisSurat.template_path')
                    ->label('Template')
                    ->state(fn(Surat $record): string => filled($record->jenisSurat?->template_path) ? 'Download' : '-')
                    ->url(fn(Surat $record): ?string => self::resolveTemplateUrl($record->jenis_surat_id))
                    ->openUrlInNewTab(),
                TextColumn::make('pembuat.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('approver.name')
                    ->label('Approver')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'booked' => 'warning',
                        'menunggu_persetujuan' => 'info',
                        'disetujui' => 'success',
                        'ditolak', 'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_surat')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('expired_at')
                    ->label('Expired At')
                    ->state(fn(Surat $record) => $record->status === Surat::STATUS_BOOKED
                        ? $record->created_at?->copy()->addDay()
                        : null)
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_surat_id')
                    ->label('Kategori')
                    ->options(fn() => KategoriSurat::query()->pluck('nama', 'id')->all())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $value) => $query->whereHas('jenisSurat', fn(Builder $jenisQuery) => $jenisQuery->where('kategori_surat_id', $value))
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function resolveTemplateUrl(?int $jenisSuratId): ?string
    {
        if (blank($jenisSuratId)) {
            return null;
        }

        $templatePath = JenisSurat::query()
            ->whereKey($jenisSuratId)
            ->value('template_path');

        if (blank($templatePath)) {
            return null;
        }

        if (Storage::disk('public')->exists($templatePath) || Storage::disk('local')->exists($templatePath)) {
            return route('templates.download', ['jenisSurat' => $jenisSuratId]);
        }

        return null;
    }
}

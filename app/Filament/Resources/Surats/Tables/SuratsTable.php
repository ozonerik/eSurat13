<?php

namespace App\Filament\Resources\Surats\Tables;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\KategoriSurat;
use App\Models\JenisSurat;
use App\Models\Surat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use function Filament\Support\original_request;

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
                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->sortable(),
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
            ->recordUrl(fn(Model $record): string => self::resolveEditUrl($record))
            ->recordActions([
                EditAction::make()
                    ->url(fn(Surat $record): string => self::resolveEditUrl($record)),
                DeleteAction::make()
                    ->visible(fn(Surat $record): bool => $record->no_surat === null && SuratResource::canDelete($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(fn (): bool => self::resolveNavigationSource() === 'surat-expired'),
                ]),
            ]);
    }

    protected static function resolveEditUrl(Model $record): string
    {
        $source = self::resolveNavigationSource();

        return SuratResource::getUrl('edit', [
            'record' => $record,
            'source' => $source,
        ]);
    }

    protected static function resolveNavigationSource(): ?string
    {
        $request = original_request();
        $routeName = $request->route()?->getName();

        return match ($routeName) {
            'filament.admin.resources.surats.draft-surats' => 'draft-surats',
            'filament.admin.resources.surats.surat-dikirim' => 'surat-dikirim',
            'filament.admin.resources.surats.surat-disetujui' => 'surat-disetujui',
            'filament.admin.resources.surats.surat-ditolak' => 'surat-ditolak',
            'filament.admin.resources.surats.surat-expired' => 'surat-expired',
            'filament.admin.resources.surats.review-surats' => 'review-surats',
            default => null,
        };
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

<?php

namespace App\Filament\Resources\Surats\Tables;

use App\Models\KategoriSurat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('pembuat.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_surat_id')
                    ->label('Kategori')
                    ->options(fn () => KategoriSurat::query()->pluck('nama', 'id')->all())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) => $query->whereHas('jenisSurat', fn (Builder $jenisQuery) => $jenisQuery->where('kategori_surat_id', $value))
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
}

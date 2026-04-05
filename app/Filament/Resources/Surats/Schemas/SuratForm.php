<?php

namespace App\Filament\Resources\Surats\Schemas;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\User;
use App\Services\SuratNumberService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis_surat_id')
                    ->label('Jenis Surat')
                    ->options(function (): array {
                        return JenisSurat::query()
                            ->with('kategoriSurat:id,kode')
                            ->orderBy('kode')
                            ->get()
                            ->mapWithKeys(fn (JenisSurat $jenisSurat): array => [
                                $jenisSurat->id => sprintf(
                                    '%s - %s - %s',
                                    $jenisSurat->kategoriSurat?->kode ?? '-',
                                    $jenisSurat->kode,
                                    $jenisSurat->nama
                                ),
                            ])
                            ->all();
                    })
                    ->getSearchResultsUsing(function (string $search): array {
                        return JenisSurat::query()
                            ->with('kategoriSurat:id,kode')
                            ->where(function (Builder $query) use ($search): void {
                                $query
                                    ->where('kode', 'like', "%{$search}%")
                                    ->orWhere('nama', 'like', "%{$search}%")
                                    ->orWhereHas('kategoriSurat', fn (Builder $kategoriQuery): Builder => $kategoriQuery->where('kode', 'like', "%{$search}%"));
                            })
                            ->orderBy('kode')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn (JenisSurat $jenisSurat): array => [
                                $jenisSurat->id => sprintf(
                                    '%s - %s - %s',
                                    $jenisSurat->kategoriSurat?->kode ?? '-',
                                    $jenisSurat->kode,
                                    $jenisSurat->nama
                                ),
                            ])
                            ->all();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $jenisSurat = JenisSurat::query()
                            ->with('kategoriSurat:id,kode')
                            ->find($value);

                        if (! $jenisSurat) {
                            return null;
                        }

                        return sprintf(
                            '%s - %s - %s',
                            $jenisSurat->kategoriSurat?->kode ?? '-',
                            $jenisSurat->kode,
                            $jenisSurat->nama
                        );
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->hintAction(
                        Action::make('download_template')
                            ->label('Download Template')
                            ->icon('heroicon-m-arrow-down-tray')
                            ->url(fn (Get $get): ?string => self::resolveTemplateUrl($get('jenis_surat_id')))
                            ->openUrlInNewTab()
                            ->visible(fn (Get $get): bool => filled(self::resolveTemplateUrl($get('jenis_surat_id'))))
                    )
                    ->required(),
                Select::make('pembuat_id')
                    ->label('Pembuat')
                    ->relationship('pembuat', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => Auth::id())
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('approver_id')
                    ->label('Approver')
                    ->relationship(
                        'approver',
                        'name',
                        fn (Builder $query): Builder => $query->role('Kepala Sekolah')
                    )
                    ->searchable()
                    ->preload()
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->required(),
                TextInput::make('no_surat')
                    ->label('No Surat')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->suffixAction(
                        Action::make('ambil_no_surat')
                            ->label('Ambil Nomor')
                            ->icon('heroicon-m-key')
                            ->action(function (Get $get, Set $set): void {
                                $jenisSuratId = $get('jenis_surat_id');

                                if (blank($jenisSuratId)) {
                                    return;
                                }

                                $jenisSurat = JenisSurat::query()->find($jenisSuratId);

                                if (! $jenisSurat) {
                                    return;
                                }

                                $noSurat = app(SuratNumberService::class)->generate($jenisSurat);

                                $set('no_surat', $noSurat);
                            })
                            ->disabled(fn (Get $get): bool => blank($get('jenis_surat_id')) || filled($get('no_surat')))
                    )
                    ->unique(ignoreRecord: true),
                TextInput::make('perihal')
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->default(now()->toDateString())
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->required(),
                FileUpload::make('surat_file_path')
                    ->label('Upload Surat')
                    ->directory('surat')
                    ->live()
                    ->acceptedFileTypes([
                        'application/pdf',
                    ])
                    ->disabled(function (string $operation, Get $get): bool {
                        if ($operation !== 'edit') {
                            return false;
                        }

                        $isPembuat = (int) Auth::id() === (int) ($get('pembuat_id') ?? 0);
                        $isBooked = $get('status') === Surat::STATUS_BOOKED;

                        return ! ($isPembuat && $isBooked);
                    })
                    ->openable()
                    ->downloadable(),
                Select::make('status')
                    ->options([
                        Surat::STATUS_DRAFT => 'draft',
                        Surat::STATUS_BOOKED => 'booked',
                        Surat::STATUS_EXPIRED => 'expired',
                        Surat::STATUS_MENUNGGU_PERSETUJUAN => 'menunggu_persetujuan',
                        Surat::STATUS_DISETUJUI => 'disetujui',
                        Surat::STATUS_DITOLAK => 'ditolak',
                    ])
                    ->default(Surat::STATUS_DRAFT)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                TextInput::make('verification_token')
                    ->disabled()
                    ->dehydrated()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('rejection_note')
                    ->visible(fn (Get $get): bool => $get('status') === Surat::STATUS_DITOLAK)
                    ->disabled(function (string $operation): bool {
                        if ($operation === 'edit') {
                            return true;
                        }

                        $user = Auth::user();

                        return ! ($user instanceof User && $user->hasRole('Kepala Sekolah'));
                    })
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    protected static function resolveTemplateUrl(mixed $jenisSuratId): ?string
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

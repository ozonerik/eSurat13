<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Models\JenisSurat;
use App\Services\SuratNumberService;
use App\Filament\Resources\Surats\SuratResource;
use App\Filament\Resources\Pages\CreateRecordRedirectIndex as CreateRecord;
use Filament\Actions\Action;

class CreateSurat extends CreateRecord
{
    protected static string $resource = SuratResource::class;

    protected bool $isSendingSuratSubmission = false;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label(fn (): string => filled(data_get($this->data, 'surat_file_path')) ? 'Kirim' : 'Buat');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['no_surat'] ?? null) && filled($data['jenis_surat_id'] ?? null)) {
            $jenisSurat = JenisSurat::query()->find($data['jenis_surat_id']);

            if ($jenisSurat) {
                $data['no_surat'] = app(SuratNumberService::class)->generate($jenisSurat);
            }
        }

        return $data;
    }

    protected function beforeCreate(): void
    {
        $this->isSendingSuratSubmission = filled(data_get($this->data, 'surat_file_path'));
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        if ($this->isSendingSuratSubmission) {
            return 'Surat berhasil dikirim';
        }

        return parent::getCreatedNotificationTitle();
    }

    protected function getRedirectUrl(): string
    {
        $sourcePage = $this->resolveSourcePageByStatus($this->record?->status);

        return static::getResource()::getUrl($sourcePage);
    }

    protected function resolveSourcePageByStatus(?string $status): string
    {
        return match ($status) {
            
            \App\Models\Surat::STATUS_BOOKED => 'draft-surats',
            \App\Models\Surat::STATUS_MENUNGGU_PERSETUJUAN => 'surat-dikirim',
            \App\Models\Surat::STATUS_DISETUJUI => 'surat-disetujui',
            \App\Models\Surat::STATUS_DITOLAK => 'surat-ditolak',
            \App\Models\Surat::STATUS_EXPIRED => 'surat-expired',
            default => 'create',
        };
    }
}

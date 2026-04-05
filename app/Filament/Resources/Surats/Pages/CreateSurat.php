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
}

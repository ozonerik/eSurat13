<?php

namespace App\Observers;

use App\Models\Surat;
use App\Services\SuratTelegramNotificationService;

class SuratObserver
{
    public function __construct(
        protected SuratTelegramNotificationService $telegramNotificationService,
    ) {
    }

    public function created(Surat $surat): void
    {
        if (filled($surat->status)) {
            $this->telegramNotificationService->notifyStatusChangedToPembuat($surat);
        }
    }

    public function updated(Surat $surat): void
    {
        if (! $surat->wasChanged('status')) {
            return;
        }

        $this->telegramNotificationService->notifyStatusChangedToPembuat(
            $surat,
            (string) $surat->getRawOriginal('status'),
        );
    }
}

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
        if ($this->isSubmittedForReviewOnCreate($surat)) {
            $this->telegramNotificationService->notifySubmittedForReview($surat);
        }
    }

    public function updated(Surat $surat): void
    {
        $isStatusChangedToReview = $surat->wasChanged('status')
            && $surat->status === Surat::STATUS_MENUNGGU_PERSETUJUAN;

        $isFileReuploadedForReview = ! $isStatusChangedToReview
            && $surat->wasChanged('surat_file_path')
            && filled($surat->surat_file_path)
            && $surat->status === Surat::STATUS_MENUNGGU_PERSETUJUAN;

        if (! $isStatusChangedToReview && ! $isFileReuploadedForReview) {
            return;
        }

        $this->telegramNotificationService->notifySubmittedForReview(
            $surat,
            $isFileReuploadedForReview,
        );
    }

    protected function isSubmittedForReviewOnCreate(Surat $surat): bool
    {
        return filled($surat->surat_file_path)
            && $surat->status === Surat::STATUS_MENUNGGU_PERSETUJUAN;
    }
}

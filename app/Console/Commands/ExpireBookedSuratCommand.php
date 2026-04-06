<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramMessageJob;
use App\Models\AuditLog;
use App\Models\Surat;
use App\Models\TelegramLog;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('surat:expire-bookings')]
#[Description('Expire booked surat yang melewati waktu expired_at tanpa upload surat')]
class ExpireBookedSuratCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredCount = 0;

        Surat::query()
            ->with('pembuat')
            ->where('status', Surat::STATUS_BOOKED)
            ->whereNull('surat_file_path')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($surats) use (&$expiredCount): void {
                foreach ($surats as $surat) {
                    DB::transaction(function () use ($surat, &$expiredCount): void {
                        $surat->refresh();

                        if (
                            $surat->status !== Surat::STATUS_BOOKED
                            || filled($surat->surat_file_path)
                            || blank($surat->expired_at)
                            || $surat->expired_at->isFuture()
                        ) {
                            return;
                        }

                        $releasedNumber = $surat->released_no_surat ?: $surat->no_surat;
                        $oldNumber = $surat->no_surat;

                        $surat->update([
                            'status' => Surat::STATUS_EXPIRED,
                            'no_surat' => null,
                            'released_no_surat' => $releasedNumber,
                        ]);

                        AuditLog::create([
                            'surat_id' => $surat->id,
                            'user_id' => null,
                            'action' => 'booking_expired',
                            'description' => 'Nomor surat dibatalkan otomatis karena melewati batas waktu expired.',
                            'old_values' => [
                                'status' => Surat::STATUS_BOOKED,
                                'no_surat' => $oldNumber,
                            ],
                            'new_values' => [
                                'status' => Surat::STATUS_EXPIRED,
                                'no_surat' => null,
                            ],
                            'logged_at' => now(),
                        ]);

                        $chatId = $surat->pembuat?->telegram_chat_id;
                        $message = sprintf(
                            "[PERINGATAN] Nomor surat *%s* telah dibatalkan otomatis karena melewati batas waktu expired.",
                            $oldNumber ?: '-'
                        );

                        $telegramLog = TelegramLog::create([
                            'surat_id' => $surat->id,
                            'user_id' => $surat->pembuat_id,
                            'chat_id' => (string) $chatId,
                            'message' => $message,
                            'status' => TelegramLog::STATUS_PENDING,
                            'retry_count' => 0,
                        ]);

                        if ($chatId) {
                            SendTelegramMessageJob::dispatch($telegramLog->id);
                        } else {
                            $telegramLog->update([
                                'status' => TelegramLog::STATUS_FAILED,
                                'failed_at' => now(),
                                'response_body' => 'User telegram_chat_id is empty.',
                            ]);
                        }

                        $expiredCount++;
                    });
                }
            });

        $this->info("{$expiredCount} surat booking expired diproses.");

        return self::SUCCESS;
    }
}

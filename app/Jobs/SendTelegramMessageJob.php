<?php

namespace App\Jobs;

use App\Models\TelegramLog;
use App\Services\TelegramBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTelegramMessageJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [10, 30, 60];

    public function __construct(public int $telegramLogId)
    {
    }

    public function handle(TelegramBotService $telegramBotService): void
    {
        $telegramLog = TelegramLog::query()->find($this->telegramLogId);

        if (! $telegramLog) {
            return;
        }

        if ($telegramLog->status === TelegramLog::STATUS_SENT) {
            return;
        }

        $result = $telegramBotService->sendMessage($telegramLog->chat_id, $telegramLog->message);

        if ($result['ok']) {
            $telegramLog->update([
                'status' => TelegramLog::STATUS_SENT,
                'sent_at' => now(),
                'failed_at' => null,
                'response_body' => $result['body'],
                'retry_count' => max(0, $this->attempts() - 1),
            ]);

            return;
        }

        $isFinalAttempt = $this->attempts() >= $this->tries;

        $telegramLog->update([
            'status' => $isFinalAttempt ? TelegramLog::STATUS_FAILED : TelegramLog::STATUS_PENDING,
            'failed_at' => $isFinalAttempt ? now() : null,
            'response_body' => $result['body'],
            'retry_count' => $this->attempts(),
        ]);

        if (! $isFinalAttempt) {
            throw new \RuntimeException('Telegram send failed, retrying.');
        }
    }
}

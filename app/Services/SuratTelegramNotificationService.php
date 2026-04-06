<?php

namespace App\Services;

use App\Jobs\SendTelegramMessageJob;
use App\Models\Surat;
use App\Models\TelegramLog;
use App\Models\User;

class SuratTelegramNotificationService
{
    public function notifySubmittedForReview(Surat $surat, bool $isRevision = false): void
    {
        $surat->loadMissing(['pembuat:id,name,telegram_chat_id', 'approver:id,name,telegram_chat_id']);

        $recipientIds = [];

        if ($surat->pembuat) {
            $recipientIds[] = (int) $surat->pembuat->id;
        }

        if ($surat->approver) {
            $recipientIds[] = (int) $surat->approver->id;
        }

        $pengelolaIds = User::query()
            ->role('Pengelola Surat')
            ->where('is_active', true)
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $recipientIds = array_values(array_unique(array_merge($recipientIds, $pengelolaIds)));

        if ($recipientIds === []) {
            return;
        }

        $users = User::query()
            ->whereIn('id', $recipientIds)
            ->get(['id', 'name', 'telegram_chat_id']);

        foreach ($users as $user) {
            $chatId = (string) ($user->telegram_chat_id ?? '');

            if (! $this->isValidTelegramChatId($chatId)) {
                continue;
            }

            $message = $this->buildMessage($surat, $user, $isRevision);

            $telegramLog = TelegramLog::create([
                'surat_id' => $surat->id,
                'user_id' => $user->id,
                'chat_id' => $chatId,
                'message' => $message,
                'status' => TelegramLog::STATUS_PENDING,
                'retry_count' => 0,
            ]);

            SendTelegramMessageJob::dispatch($telegramLog->id)->afterCommit();
        }
    }

    protected function isValidTelegramChatId(string $chatId): bool
    {
        return preg_match('/^-?\d{5,32}$/', $chatId) === 1;
    }

    protected function buildMessage(Surat $surat, User $recipient, bool $isRevision): string
    {
        $statusLabel = $isRevision ? 'Revisi Surat Dikirim' : 'Surat Dikirim';
        $isApprover = (int) $recipient->id === (int) $surat->approver_id;
        $isPembuat = (int) $recipient->id === (int) $surat->pembuat_id;

        $actionText = match (true) {
            $isApprover => 'Ada surat yang perlu Anda review.',
            $isPembuat => 'Surat Anda berhasil dikirim dan menunggu persetujuan.',
            default => 'Ada surat baru yang perlu direview.',
        };

        $noSurat = $this->escapeMarkdown((string) ($surat->no_surat ?? '-'));
        $perihal = $this->escapeMarkdown((string) ($surat->perihal ?? '-'));
        $pembuat = $this->escapeMarkdown((string) ($surat->pembuat?->name ?? '-'));
        $approver = $this->escapeMarkdown((string) ($surat->approver?->name ?? '-'));

        return implode("\n", [
            '*[E-SURAT] ' . $statusLabel . '*',
            $actionText,
            '',
            '*No Surat:* ' . $noSurat,
            '*Perihal:* ' . $perihal,
            '*Pembuat:* ' . $pembuat,
            '*Approver:* ' . $approver,
            '*Status:* menunggu persetujuan',
            '*Waktu:* ' . now()->format('d M Y H:i'),
        ]);
    }

    protected function escapeMarkdown(string $text): string
    {
        return (string) preg_replace('/([_\*\[\]\(\)])/u', '\\\\$1', $text);
    }
}

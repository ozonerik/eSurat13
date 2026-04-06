<?php

namespace App\Services;

use App\Jobs\SendTelegramMessageJob;
use App\Models\Surat;
use App\Models\TelegramLog;
use App\Models\User;

class SuratTelegramNotificationService
{
    public function notifyStatusChangedToPembuat(Surat $surat, ?string $oldStatus = null): void
    {
        $surat->loadMissing(['pembuat:id,name,telegram_chat_id', 'approver:id,name']);

        $pembuat = $surat->pembuat;

        if (! $pembuat) {
            return;
        }

        $chatId = (string) ($pembuat->telegram_chat_id ?? '');

        if (! $this->isValidTelegramChatId($chatId)) {
            return;
        }

        $message = $this->buildStatusChangedMessage($surat, $oldStatus);

        $telegramLog = TelegramLog::create([
            'surat_id' => $surat->id,
            'user_id' => $pembuat->id,
            'chat_id' => $chatId,
            'message' => $message,
            'status' => TelegramLog::STATUS_PENDING,
            'retry_count' => 0,
        ]);

        SendTelegramMessageJob::dispatch($telegramLog->id)->afterCommit();
    }

    public function notifySubmittedForReview(Surat $surat, bool $isRevision = false): void
    {
        $this->dispatchToRecipients(
            $surat,
            fn (Surat $currentSurat, User $recipient): string => $this->buildReviewMessage($currentSurat, $recipient, $isRevision),
        );
    }

    public function notifyApproved(Surat $surat): void
    {
        $this->dispatchToRecipients(
            $surat,
            fn (Surat $currentSurat, User $recipient): string => $this->buildApprovedMessage($currentSurat, $recipient),
        );
    }

    public function notifyRejected(Surat $surat): void
    {
        $this->dispatchToRecipients(
            $surat,
            fn (Surat $currentSurat, User $recipient): string => $this->buildRejectedMessage($currentSurat, $recipient),
        );
    }

    public function notifyBooked(Surat $surat): void
    {
        $this->dispatchToRecipients(
            $surat,
            fn (Surat $currentSurat, User $recipient): string => $this->buildBookedMessage($currentSurat, $recipient),
        );
    }

    public function notifyExpired(Surat $surat): void
    {
        $this->dispatchToRecipients(
            $surat,
            fn (Surat $currentSurat, User $recipient): string => $this->buildExpiredMessage($currentSurat, $recipient),
        );
    }

    /**
     * @param  callable(Surat, User): string  $messageBuilder
     */
    protected function dispatchToRecipients(Surat $surat, callable $messageBuilder): void
    {
        $surat->loadMissing(['pembuat:id,name,telegram_chat_id', 'approver:id,name,telegram_chat_id']);

        $users = $this->collectRecipients($surat);

        foreach ($users as $user) {
            $chatId = (string) ($user->telegram_chat_id ?? '');

            if (! $this->isValidTelegramChatId($chatId)) {
                continue;
            }

            $telegramLog = TelegramLog::create([
                'surat_id' => $surat->id,
                'user_id' => $user->id,
                'chat_id' => $chatId,
                'message' => $messageBuilder($surat, $user),
                'status' => TelegramLog::STATUS_PENDING,
                'retry_count' => 0,
            ]);

            SendTelegramMessageJob::dispatch($telegramLog->id)->afterCommit();
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    protected function collectRecipients(Surat $surat)
    {
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
            return collect();
        }

        return User::query()
            ->whereIn('id', $recipientIds)
            ->get(['id', 'name', 'telegram_chat_id']);
    }

    protected function isValidTelegramChatId(string $chatId): bool
    {
        return preg_match('/^-?\d{5,32}$/', $chatId) === 1;
    }

    protected function buildReviewMessage(Surat $surat, User $recipient, bool $isRevision): string
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

    protected function buildApprovedMessage(Surat $surat, User $recipient): string
    {
        $isPembuat = (int) $recipient->id === (int) $surat->pembuat_id;
        $actionText = $isPembuat
            ? 'Surat Anda telah disetujui.'
            : 'Ada surat yang telah disetujui.';

        return $this->buildDecisionMessage(
            $surat,
            'Surat Disetujui',
            $actionText,
            'disetujui',
        );
    }

    protected function buildRejectedMessage(Surat $surat, User $recipient): string
    {
        $isPembuat = (int) $recipient->id === (int) $surat->pembuat_id;
        $actionText = $isPembuat
            ? 'Surat Anda ditolak. Silakan periksa catatan penolakan.'
            : 'Ada surat yang ditolak dan membutuhkan tindak lanjut.';

        $note = trim((string) ($surat->rejection_note ?? ''));

        return $this->buildDecisionMessage(
            $surat,
            'Surat Ditolak',
            $actionText,
            'ditolak',
            $note !== '' ? $note : null,
        );
    }

    protected function buildBookedMessage(Surat $surat, User $recipient): string
    {
        $isPembuat = (int) $recipient->id === (int) $surat->pembuat_id;
        $actionText = $isPembuat
            ? 'Surat Anda berada pada status booked.'
            : 'Ada surat yang masuk status booked.';

        return $this->buildDecisionMessage(
            $surat,
            'Surat Booked',
            $actionText,
            'booked',
        );
    }

    protected function buildExpiredMessage(Surat $surat, User $recipient): string
    {
        $isPembuat = (int) $recipient->id === (int) $surat->pembuat_id;
        $actionText = $isPembuat
            ? 'Surat Anda expired karena melewati batas waktu.'
            : 'Ada surat yang berubah ke status expired.';

        return $this->buildDecisionMessage(
            $surat,
            'Surat Expired',
            $actionText,
            'expired',
        );
    }

    protected function buildDecisionMessage(
        Surat $surat,
        string $statusLabel,
        string $actionText,
        string $status,
        ?string $note = null,
    ): string {
        $noSurat = $this->escapeMarkdown((string) ($surat->no_surat ?? $surat->released_no_surat ?? '-'));
        $perihal = $this->escapeMarkdown((string) ($surat->perihal ?? '-'));
        $pembuat = $this->escapeMarkdown((string) ($surat->pembuat?->name ?? '-'));
        $approver = $this->escapeMarkdown((string) ($surat->approver?->name ?? '-'));

        $lines = [
            '*[E-SURAT] ' . $statusLabel . '*',
            $actionText,
            '',
            '*No Surat:* ' . $noSurat,
            '*Perihal:* ' . $perihal,
            '*Pembuat:* ' . $pembuat,
            '*Approver:* ' . $approver,
            '*Status:* ' . $status,
        ];

        if ($note !== null) {
            $lines[] = '*Catatan:* ' . $this->escapeMarkdown($note);
        }

        $lines[] = '*Waktu:* ' . now()->format('d M Y H:i');

        return implode("\n", $lines);
    }

    protected function escapeMarkdown(string $text): string
    {
        return (string) preg_replace('/([_\*\[\]\(\)])/u', '\\\\$1', $text);
    }

    protected function buildStatusChangedMessage(Surat $surat, ?string $oldStatus = null): string
    {
        $noSurat = $this->escapeMarkdown((string) ($surat->no_surat ?? $surat->released_no_surat ?? '-'));
        $perihal = $this->escapeMarkdown((string) ($surat->perihal ?? '-'));
        $pembuat = $this->escapeMarkdown((string) ($surat->pembuat?->name ?? '-'));
        $approver = $this->escapeMarkdown((string) ($surat->approver?->name ?? '-'));
        $newStatusLabel = $this->statusLabel((string) $surat->status);

        $lines = [
            '*[E-SURAT] Perubahan Status Surat*',
            'Status surat Anda telah berubah.',
            '',
            '*No Surat:* ' . $noSurat,
            '*Perihal:* ' . $perihal,
            '*Pembuat:* ' . $pembuat,
            '*Approver:* ' . $approver,
        ];

        if (filled($oldStatus)) {
            $lines[] = '*Status Sebelumnya:* ' . $this->statusLabel((string) $oldStatus);
        }

        $lines[] = '*Status Baru:* ' . $newStatusLabel;

        if ($surat->status === Surat::STATUS_DITOLAK && filled($surat->rejection_note)) {
            $lines[] = '*Catatan:* ' . $this->escapeMarkdown((string) $surat->rejection_note);
        }

        $lines[] = '*Waktu:* ' . now()->format('d M Y H:i');

        return implode("\n", $lines);
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            Surat::STATUS_DRAFT => 'Draft',
            Surat::STATUS_BOOKED => 'Booked',
            Surat::STATUS_MENUNGGU_PERSETUJUAN => 'Menunggu Persetujuan',
            Surat::STATUS_DISETUJUI => 'Disetujui',
            Surat::STATUS_DITOLAK => 'Ditolak',
            Surat::STATUS_EXPIRED => 'Expired',
            default => $status,
        };
    }
}

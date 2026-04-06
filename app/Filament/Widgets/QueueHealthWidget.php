<?php

namespace App\Filament\Widgets;

use App\Models\TelegramLog;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueueHealthWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Queue Health Check';

    protected ?string $pollingInterval = '10s';

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('processOneJob')
                ->label('Proses 1 Job')
                ->icon('heroicon-m-play')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->canProcessOneJob())
                ->action(function (): void {
                    Artisan::call('queue:work', [
                        '--once' => true,
                    ]);

                    $this->cachedStats = null;

                    Notification::make()
                        ->success()
                        ->title('Queue worker dijalankan sekali')
                        ->body('Satu proses queue:work --once telah dieksekusi.')
                        ->send();
                }),
        ];
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $queueDriver = (string) config('queue.default');
        $jobsTableExists = Schema::hasTable('jobs');
        $failedJobsTableExists = Schema::hasTable('failed_jobs');

        $pendingJobs = $jobsTableExists
            ? (int) DB::table('jobs')->count()
            : 0;

        $failedJobs = $failedJobsTableExists
            ? (int) DB::table('failed_jobs')->count()
            : 0;
        $telegramLogsTableExists = Schema::hasTable('telegram_logs');

        $oldestPendingTimestamp = null;
        $lastTelegramSentAt = $telegramLogsTableExists
            ? TelegramLog::query()
                ->whereNotNull('sent_at')
                ->latest('sent_at')
                ->value('sent_at')
            : null;

        if ($jobsTableExists && $pendingJobs > 0) {
            $oldestPendingTimestamp = DB::table('jobs')->min('created_at');
        }

        $oldestPendingAge = is_numeric($oldestPendingTimestamp)
            ? max(0, now()->timestamp - (int) $oldestPendingTimestamp)
            : null;

        [$statusLabel, $statusColor] = $this->resolveQueueStatus($queueDriver, $pendingJobs, $failedJobs, $oldestPendingAge);

        $pendingDescription = $oldestPendingAge === null
            ? 'Tidak ada job tertunda.'
            : 'Job tertua tertunda selama ' . $this->formatDuration($oldestPendingAge) . '.';

        return [
            Stat::make('Status Queue', $statusLabel)
                ->description('Driver: ' . $queueDriver)
                ->color($statusColor),
            Stat::make('Pending Jobs', (string) $pendingJobs)
                ->description($pendingDescription)
                ->color($this->resolvePendingColor($pendingJobs, $oldestPendingAge)),
            Stat::make('Failed Jobs', (string) $failedJobs)
                ->description($failedJobs > 0 ? 'Perlu ditangani segera.' : 'Tidak ada job gagal.')
                ->color($failedJobs > 0 ? 'danger' : 'success'),
            Stat::make('Last Telegram Sent', $this->formatLastTelegramSentValue($lastTelegramSentAt))
                ->description($this->formatLastTelegramSentDescription($lastTelegramSentAt))
                ->color($this->resolveLastTelegramSentColor($lastTelegramSentAt)),
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function resolveQueueStatus(string $queueDriver, int $pendingJobs, int $failedJobs, ?int $oldestPendingAge): array
    {
        if ($queueDriver === 'sync') {
            return ['SYNC (langsung)', 'info'];
        }

        if ($failedJobs > 0) {
            return ['Ada job gagal', 'danger'];
        }

        if ($pendingJobs === 0) {
            return ['Sehat', 'success'];
        }

        if (($oldestPendingAge ?? 0) >= 300) {
            return ['Kritis: worker kemungkinan tidak berjalan', 'danger'];
        }

        if (($oldestPendingAge ?? 0) >= 120) {
            return ['Antrian menumpuk', 'warning'];
        }

        return ['Aktif', 'info'];
    }

    protected function resolvePendingColor(int $pendingJobs, ?int $oldestPendingAge): string
    {
        if ($pendingJobs === 0) {
            return 'success';
        }

        if (($oldestPendingAge ?? 0) >= 300) {
            return 'danger';
        }

        if (($oldestPendingAge ?? 0) >= 120) {
            return 'warning';
        }

        return 'info';
    }

    protected function canProcessOneJob(): bool
    {
        return $this->isAdminUser(Auth::user())
            && config('queue.default') !== 'sync'
            && Schema::hasTable('jobs')
            && DB::table('jobs')->exists();
    }

    protected function isAdminUser(mixed $user): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        return $user->hasRole('Admin');
    }

    protected function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . ' detik';
        }

        $minutes = intdiv($seconds, 60);

        if ($minutes < 60) {
            return $minutes . ' menit';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . ' jam';
        }

        return $hours . ' jam ' . $remainingMinutes . ' menit';
    }

    protected function formatLastTelegramSentValue(mixed $sentAt): string
    {
        if (! $sentAt) {
            return 'Belum ada';
        }

        return now()->parse($sentAt)->format('d M Y H:i');
    }

    protected function formatLastTelegramSentDescription(mixed $sentAt): string
    {
        if (! $sentAt) {
            return 'Belum ada notifikasi Telegram yang sukses dikirim.';
        }

        $diffInSeconds = now()->diffInSeconds(now()->parse($sentAt));

        return 'Terakhir sukses ' . $this->formatDuration($diffInSeconds) . ' yang lalu.';
    }

    protected function resolveLastTelegramSentColor(mixed $sentAt): string
    {
        if (! $sentAt) {
            return 'gray';
        }

        $diffInSeconds = now()->diffInSeconds(now()->parse($sentAt));

        if ($diffInSeconds >= 3600) {
            return 'warning';
        }

        return 'success';
    }
}

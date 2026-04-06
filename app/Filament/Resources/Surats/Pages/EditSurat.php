<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\Surat;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use App\Filament\Resources\Pages\EditRecordRedirectIndex as EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSurat extends EditRecord
{
    protected static string $resource = SuratResource::class;

    public ?string $initialSuratFilePath = null;

    public ?string $navigationSource = null;

    public bool $hasUploadedRevision = false;

    protected bool $isRevisionSubmission = false;

    protected bool $isSendSubmission = false;

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label(fn (): string => $this->hasRevisedUpload()
                ? 'Kirim Revisi'
                : (filled(data_get($this->data, 'surat_file_path')) ? 'Kirim' : 'Simpan'))
            ->visible(fn (): bool => $this->isSaveActionVisible());
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        if ($this->isEditingFromSuratExpired()) {
            return [
                Action::make('hapus_surat_expired')
                    ->label('Hapus')
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->requiresConfirmation()
                    ->visible(fn (): bool => SuratResource::canDelete($this->record))
                    ->action(function (): mixed {
                        $this->delete();

                        return $this->redirect(static::getResource()::getUrl('surat-expired'));
                    }),
                $this->getCancelFormAction(),
            ];
        }

        return parent::getFormActions();
    }

    protected function beforeSave(): void
    {
        $hasRevisedUpload = $this->hasRevisedUpload();

        $this->isRevisionSubmission = $this->isRevisionMode() && $hasRevisedUpload;
        $this->isSendSubmission = ! $this->isRevisionSubmission && filled(data_get($this->data, 'surat_file_path'));
    }

    protected function getSavedNotificationTitle(): ?string
    {
        if ($this->isRevisionSubmission) {
            return 'Revisi Surat berhasil dikirim';
        }

        if ($this->isSendSubmission) {
            return 'Surat berhasil dikirim';
        }

        return parent::getSavedNotificationTitle();
    }

    protected function getRedirectUrl(): string
    {
        $sourcePage = $this->navigationSource ?? $this->resolveSourcePageByStatus($this->record?->status);

        return static::getResource()::getUrl($sourcePage);
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->navigationSource = request()->query('source');
        $this->initialSuratFilePath = $this->normalizeUploadState($this->record->surat_file_path);
        $this->hasUploadedRevision = false;

        $user = Auth::user();

        if (! $user instanceof User) {
            return;
        }

        if ((int) $this->record->pembuat_id === (int) $user->id) {
            $this->record->markViewedByPembuatForCurrentStatus();
        }

        if ((int) $this->record->approver_id === (int) $user->id) {
            $this->record->markViewedByApproverForCurrentStatus();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setujui')
                ->label('Setujui')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->canApprove())
                ->action(function (): void {
                    $this->record->update([
                        'status' => Surat::STATUS_DISETUJUI,
                    ]);

                    $redirectUrl = static::getResource()::getUrl('review-surats');

                    $this->redirect($redirectUrl);
                }),
            Action::make('tolak')
                ->label('Tolak')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->visible(fn (): bool => $this->canApprove())
                ->modal(true)
                ->requiresConfirmation()
                ->modalHeading('Catatan Penolakan')
                ->modalDescription('Silakan isi catatan penolakan surat.')
                ->modalSubmitActionLabel('Tolak Surat')
                ->form([
                    Textarea::make('rejection_note')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $releasedNumber = $this->record->released_no_surat ?: $this->record->no_surat;

                    $this->record->update([
                        'status' => Surat::STATUS_DITOLAK,
                        'no_surat' => null,
                        'released_no_surat' => $releasedNumber,
                        'rejection_note' => $data['rejection_note'] ?? null,
                    ]);

                    $redirectUrl = static::getResource()::getUrl('review-surats');

                    $this->redirect($redirectUrl);
                }),
        ];
    }

    protected function canApprove(): bool
    {
        $user = Auth::user();

        if ($this->navigationSource !== 'review-surats') {
            return false;
        }

        if (! $user instanceof User) {
            return false;
        }

        if ($this->record->status !== Surat::STATUS_MENUNGGU_PERSETUJUAN) {
            return false;
        }

        if ($user->can('surat.review.update.all')) {
            return true;
        }

        return $user->can('surat.review.update.assigned')
            && ((int) $this->record->approver_id === (int) $user->id);
    }

    protected function isEditingFromSuratDikirim(): bool
    {
        return $this->navigationSource === 'surat-dikirim';
    }

    protected function isEditingFromSuratDitolak(): bool
    {
        return $this->navigationSource === 'surat-ditolak';
    }

    protected function isEditingFromSuratExpired(): bool
    {
        return $this->navigationSource === 'surat-expired';
    }

    protected function hasRevisedUpload(): bool
    {
        if ($this->hasUploadedRevision) {
            return true;
        }

        $uploadState = data_get($this->data, 'surat_file_path');

        if ($this->containsTemporaryUpload($uploadState)) {
            return true;
        }

        $currentPaths = $this->extractUploadPaths($uploadState);

        if ($currentPaths === []) {
            return false;
        }

        if (count($currentPaths) > 1) {
            return true;
        }

        return $currentPaths[0] !== $this->initialSuratFilePath;
    }

    public function updatedDataSuratFilePath(mixed $state): void
    {
        if ($this->containsTemporaryUpload($state)) {
            $this->hasUploadedRevision = true;

            return;
        }

        $currentPath = $this->normalizeUploadState($state);

        $this->hasUploadedRevision = filled($currentPath)
            && ($currentPath !== $this->initialSuratFilePath);
    }

    protected function containsTemporaryUpload(mixed $state): bool
    {
        if (is_object($state)) {
            return method_exists($state, 'store') || method_exists($state, 'temporaryUrl');
        }

        if (! is_array($state)) {
            return false;
        }

        foreach ($state as $value) {
            if ($this->containsTemporaryUpload($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string>
     */
    protected function extractUploadPaths(mixed $state): array
    {
        if (blank($state)) {
            return [];
        }

        if (is_string($state)) {
            return [$state];
        }

        if (! is_array($state)) {
            return [];
        }

        $paths = [];

        foreach ($state as $key => $value) {
            if ($key === 'path' && is_string($value)) {
                $paths[] = $value;

                continue;
            }

            foreach ($this->extractUploadPaths($value) as $nestedPath) {
                $paths[] = $nestedPath;
            }
        }

        return array_values(array_unique($paths));
    }

    protected function normalizeUploadState(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        if (is_string($state)) {
            return $state;
        }

        if (! is_array($state)) {
            return null;
        }

        if (array_key_exists('path', $state) && is_string($state['path'])) {
            return $state['path'];
        }

        $firstValue = reset($state);

        return $this->normalizeUploadState($firstValue);
    }

    protected function isSaveActionVisible(): bool
    {
        if ($this->isEditingFromSuratExpired()) {
            return false;
        }

        if ($this->isApprovedReadonlyContext()) {
            return false;
        }

        if ($this->navigationSource === 'review-surats') {
            return false;
        }

        if ($this->isRevisionMode()) {
            return $this->hasRevisedUpload();
        }

        return true;
    }

    protected function isRevisionMode(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        $isFromRevisionSource = $this->isEditingFromSuratDikirim()
            || $this->isEditingFromSuratDitolak()
            || blank($this->navigationSource);
        $isPembuat = (int) $this->record->pembuat_id === (int) $user->id;
        $isRevisableStatus = in_array($this->record->status, [
            Surat::STATUS_MENUNGGU_PERSETUJUAN,
            Surat::STATUS_DITOLAK,
        ], true);

        return $isFromRevisionSource && $isPembuat && $isRevisableStatus;
    }

    protected function isApprovedReadonlyContext(): bool
    {
        return $this->navigationSource === 'surat-disetujui'
            && $this->record->status === Surat::STATUS_DISETUJUI;
    }

    protected function resolveSourcePageByStatus(?string $status): string
    {
        return match ($status) {
            Surat::STATUS_BOOKED => 'draft-surats',
            Surat::STATUS_MENUNGGU_PERSETUJUAN => 'surat-dikirim',
            Surat::STATUS_DISETUJUI => 'surat-disetujui',
            Surat::STATUS_DITOLAK => 'surat-ditolak',
            Surat::STATUS_EXPIRED => 'surat-expired',
            default => 'create',
        };
    }
}

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
                }),
            Action::make('tolak')
                ->label('Tolak')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->visible(fn (): bool => $this->canApprove())
                ->schema([
                    Textarea::make('rejection_note')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => Surat::STATUS_DITOLAK,
                        'rejection_note' => $data['rejection_note'] ?? null,
                    ]);
                }),
        ];
    }

    protected function canApprove(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->hasRole('Kepala Sekolah')) {
            return false;
        }

        return ($this->record->status === Surat::STATUS_MENUNGGU_PERSETUJUAN)
            && ((int) $this->record->approver_id === (int) $user->id);
    }
}

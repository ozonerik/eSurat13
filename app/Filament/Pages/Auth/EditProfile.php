<?php

namespace App\Filament\Pages\Auth;

use App\Services\TelegramBotService;
use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public static function getLabel(): string
    {
        return 'Update Profile';
    }

    public function getTitle(): string
    {
        return static::getLabel();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->live(debounce: 500),
                TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(30)
                    ->unique(ignoreRecord: true),
                TextInput::make('pangkat_golongan')
                    ->label('Pangkat/Golongan')
                    ->maxLength(255),
                TextInput::make('telp')
                    ->label('Telepon')
                    ->maxLength(255),
                TextInput::make('telegram_chat_id')
                    ->label('Telegram Chat ID')
                    ->default(null)
                    ->maxLength(32)
                    ->suffixAction(
                        Action::make('get_my_chat_id')
                            ->label('Get My Chat ID')
                            ->icon('heroicon-m-arrow-path')
                            ->action(function (Set $set): void {
                                $payload = 'profile_' . $this->getUser()->getKey();
                                $chatId = app(TelegramBotService::class)->getChatIdFromStartPayload($payload);

                                if ($chatId !== null) {
                                    $set('telegram_chat_id', $chatId);

                                    Notification::make()
                                        ->success()
                                        ->title('Telegram Chat ID ditemukan')
                                        ->body('Chat ID berhasil diambil dari Telegram bot. Silakan klik Simpan.')
                                        ->send();

                                    return;
                                }

                                $botName = ltrim((string) config('services.telegram.bot_name', ''), '@');
                                $startUrl = $botName !== ''
                                    ? sprintf('https://t.me/%s?start=%s', $botName, $payload)
                                    : null;

                                $instruction = $startUrl
                                    ? 'Buka tautan bot, kirim perintah /start, lalu klik tombol ini lagi: ' . $startUrl
                                    : 'Pastikan TELEGRAM_BOT_NAME dan TELEGRAM_BOT_TOKEN sudah terisi, kirim /start ke bot, lalu klik tombol ini lagi.';

                                Notification::make()
                                    ->warning()
                                    ->title('Chat ID belum ditemukan')
                                    ->body($instruction)
                                    ->persistent()
                                    ->send();
                            })
                    )
                    ->unique(ignoreRecord: true),
                FileUpload::make('tanda_tangan')
                    ->label('Tanda Tangan')
                    ->image()
                    ->directory('tanda-tangan')
                    ->openable()
                    ->downloadable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}

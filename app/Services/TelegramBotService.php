<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class TelegramBotService
{
    /**
     * @return array{ok: bool, body: string}
     */
    public function sendMessage(string $chatId, string $message): array
    {
        $token = (string) config('services.telegram.bot_token');

        if ($token === '') {
            return [
                'ok' => false,
                'body' => 'Telegram bot token is not configured.',
            ];
        }

        $baseUrl = rtrim((string) config('services.telegram.base_url', 'https://api.telegram.org'), '/');
        $url = sprintf('%s/bot%s/sendMessage', $baseUrl, $token);

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post($url, [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                    'disable_web_page_preview' => true,
                ]);

            return [
                'ok' => $response->successful() && (bool) data_get($response->json(), 'ok', false),
                'body' => $response->body(),
            ];
        } catch (Throwable $throwable) {
            return [
                'ok' => false,
                'body' => $throwable->getMessage(),
            ];
        }
    }

    public function getChatIdFromStartPayload(string $payload): ?string
    {
        $token = (string) config('services.telegram.bot_token');

        if ($token === '' || $payload === '') {
            return null;
        }

        $baseUrl = rtrim((string) config('services.telegram.base_url', 'https://api.telegram.org'), '/');
        $url = sprintf('%s/bot%s/getUpdates', $baseUrl, $token);

        try {
            $response = Http::timeout(15)->get($url, [
                'limit' => 100,
                'allowed_updates' => json_encode(['message']),
            ]);

            if (! $response->successful() || ! (bool) data_get($response->json(), 'ok', false)) {
                return null;
            }

            $updates = data_get($response->json(), 'result', []);

            if (! is_array($updates)) {
                return null;
            }

            foreach (array_reverse($updates) as $update) {
                $text = (string) data_get($update, 'message.text', '');

                if ($text !== '/start '.$payload) {
                    continue;
                }

                $chatId = data_get($update, 'message.chat.id');

                if ($chatId === null) {
                    continue;
                }

                return (string) $chatId;
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }
}

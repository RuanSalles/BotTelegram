<?php

namespace App\Telegram\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(int $chatId, string $text): void
    {
        $token = config('services.telegram.token');

        Http::timeout(10)
            ->retry(2, 200)
            ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
    }
}
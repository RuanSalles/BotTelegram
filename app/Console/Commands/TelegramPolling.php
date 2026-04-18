<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramPolling extends Command
{
    protected $signature = 'telegram:poll';
    protected $description = 'Start Telegram bot polling';

    public function handle()
    {
        $token = config('services.telegram.token');
        $offset = cache()->get('telegram_offset', 0);

        $this->info('🤖 Telegram polling iniciado...');

        while (true) {
            $response = Http::get("https://api.telegram.org/bot{$token}/getUpdates", [
                'offset' => $offset,
                'timeout' => 30,
            ]);

            $updates = $response->json()['result'] ?? [];

            foreach ($updates as $update) {
                $offset = $update['update_id'] + 1;

                $message = $update['message']['text'] ?? null;
                $chatId = $update['message']['chat']['id'] ?? null;

                if (!$message || !$chatId) {
                    continue;
                }

                $this->info("Mensagem: {$message}");

                switch (true) {

                    case $message === '/start':
                        $this->sendMessage($token, $chatId, "👋 Bem-vindo!\nDigite /help para ver os comandos.");
                        break;

                    case $message === '/help':
                        $this->sendMessage($token, $chatId, "📚 Comandos disponíveis:\n/start\n/help\n/lesson");
                        break;

                    case $message === '/lesson':
                        $this->sendMessage($token, $chatId, "🇬🇧 Aula rápida:\n\n'Hello' = Olá\n\nRepita: Hello!");
                        break;

                    default:
                        $this->sendMessage($token, $chatId, "❌ Não entendi.\nDigite /help.");
                        break;
                }
            }

            sleep(1);
        }
    }

    private function sendMessage($token, $chatId, $text)
    {
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
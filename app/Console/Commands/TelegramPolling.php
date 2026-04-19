<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Telegram\Services\CommandBus;

class TelegramPolling extends Command
{
    protected $signature = 'telegram:poll';
    protected $description = 'Start Telegram bot polling';

    public function handle()
    {
        $token = config('services.telegram.token');

        $offset = cache()->get('telegram_offset', 0);

        $this->info("🤖 Telegram bot iniciado...");

        while (true) {

            try {
                $response = Http::timeout(30)
                    ->get("https://api.telegram.org/bot{$token}/getUpdates", [
                        'offset' => $offset,
                        'timeout' => 25,
                    ]);

                if ($response->failed()) {
                    $this->warn("Falha ao buscar updates");
                    sleep(2);
                    continue;
                }

                $updates = $response->json('result') ?? [];

                $bus = app(CommandBus::class);

                foreach ($updates as $update) {

                    $offset = $update['update_id'] + 1;

                    $message = $update['message']['text'] ?? null;
                    $chatId = $update['message']['chat']['id'] ?? null;

                    if (!$message || !$chatId) {
                        continue;
                    }

                    $this->info("📩 {$message}");

                    $bus->handle($chatId, $message);
                }

                cache()->forever('telegram_offset', $offset);

            } catch (\Throwable $e) {
                $this->error("Erro: " . $e->getMessage());
                sleep(3);
            }

            sleep(1);
        }
    }
}
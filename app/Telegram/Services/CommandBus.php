<?php

namespace App\Telegram\Services;

use App\AI\AIService;

class CommandBus
{
    public function __construct(
        private TelegramService $telegram,
        private AIService $ai
    ) {}

    public function handle(int $chatId, string $message): void
    {
        $message = trim($message);

        match ($message) {

            '/start' => $this->start($chatId),

            '/help' => $this->help($chatId),

            '/lesson' => $this->lesson($chatId),

            default => $this->aiResponse($chatId, $message),
        };
    }

    private function start(int $chatId): void
    {
        $this->telegram->sendMessage(
            $chatId,
            "👋 Olá! Eu sou seu professor de inglês com IA.\n\n"
            . "💡 Escreva qualquer frase em inglês e eu vou corrigir para você."
        );
    }

    private function help(int $chatId): void
    {
        $this->telegram->sendMessage(
            $chatId,
            "📚 Comandos disponíveis:\n\n"
            . "/start - iniciar\n"
            . "/help - ajuda\n"
            . "/lesson - aula rápida\n\n"
            . "💡 Ou apenas envie frases em inglês para correção."
        );
    }

    private function lesson(int $chatId): void
    {
        $this->telegram->sendMessage(
            $chatId,
            "🇬🇧 Aula rápida:\n\n"
            . "❌ I go to school yesterday\n"
            . "✔ I went to school yesterday\n\n"
            . "💡 Use passado simples para ações no passado."
        );
    }

    private function aiResponse(int $chatId, string $message): void
    {
        try {
            $reply = $this->ai->reply($message);

            $this->telegram->sendMessage($chatId, $reply);

        } catch (\Throwable $e) {

            $this->telegram->sendMessage(
                $chatId,
                "⚠️ Erro ao processar sua mensagem. Tente novamente."
            );
        }
    }
}
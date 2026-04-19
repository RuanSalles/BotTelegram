<?php

namespace App\Telegram\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function replyAsEnglishTeacher(string $message): string
    {
        $apiKey = config('services.openai.key');

        $response = Http::withToken($apiKey)
            ->timeout(15)
            ->retry(2, 200)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an English teacher. Correct mistakes, explain briefly in Portuguese, and provide the correct English sentence.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return "⚠️ Não consegui processar sua mensagem agora. Tente novamente.";
        }

        return $response->json('choices.0.message.content')
            ?? "Não consegui gerar resposta.";
    }
}
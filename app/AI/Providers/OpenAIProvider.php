<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;

class OpenAIProvider implements AIProviderInterface
{
    public function reply(string $message): string
    {
        $key = config('services.openai.key');

        $response = Http::withToken($key)
            ->post("https://api.openai.com/v1/chat/completions", [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an English teacher'],
                    ['role' => 'user', 'content' => $message],
                ]
            ]);

        return data_get(
            $response->json(),
            'choices.0.message.content',
            'Erro OpenAI'
        );
    }
}
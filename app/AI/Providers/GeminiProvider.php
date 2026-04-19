<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;

class GeminiProvider implements AIProviderInterface
{
    public function reply(string $message): string
    {
        $key = config('services.gemini.key');

        $response = Http::timeout(20)
            ->post(
                "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$key}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $this->prompt($message)
                                ]
                            ]
                        ]
                    ]
                ]
            );

        if ($response->failed()) {
            return "❌ Erro Gemini: " . $response->body();
        }

        return data_get(
            $response->json(),
            'candidates.0.content.parts.0.text',
            'Resposta vazia'
        );
    }

    private function prompt(string $message): string
    {
        return
            "You are a friendly English teacher having a natural conversation with a student.\n\n"
            . "Your behavior rules:\n"
            . "- Talk naturally in English like a real teacher in chat.\n"
            . "- If the student makes mistakes, ALWAYS correct them.\n"
            . "- Explain the mistake briefly in Portuguese.\n"
            . "- Provide the corrected sentence in English.\n"
            . "- Continue the conversation after the correction with a relevant question.\n"
            . "- Adapt tone (formal or informal) based on the student's style.\n"
            . "- Keep responses short and conversational.\n\n"
            . "Response format:\n"
            . "1. Correction (if needed)\n"
            . "2. Explanation (Portuguese)\n"
            . "3. Natural continuation question in English\n\n"
            . "Student message:\n"
            . "{$message}";
    }
}
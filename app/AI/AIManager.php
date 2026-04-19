<?php

namespace App\AI;

use App\AI\Contracts\AIProviderInterface;
use App\AI\Providers\GeminiProvider;
use App\AI\Providers\OpenAIProvider;
use App\AI\Providers\GroqProvider;

class AIManager
{
    public function resolve(): AIProviderInterface
    {
        return match (config('ai.provider')) {
            //            'openai' => app(OpenAIProvider::class),
//            'groq'   => app(GroqProvider::class),

            default => app(GeminiProvider::class),
        };
    }
}
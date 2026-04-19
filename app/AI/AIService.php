<?php

namespace App\AI;

class AIService
{
    public function __construct(
        private AIManager $manager
    ) {}

    public function reply(string $message): string
    {
        return $this->manager
            ->resolve()
            ->reply($message);
    }
}
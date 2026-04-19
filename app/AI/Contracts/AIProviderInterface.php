<?php

namespace App\AI\Contracts;

interface AIProviderInterface
{
    public function reply(string $message): string;
}
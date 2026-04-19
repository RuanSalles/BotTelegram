<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Provider de IA padrão
    |--------------------------------------------------------------------------
    |
    | Define qual provider será usado pelo AIManager.
    | Valores possíveis: gemini, openai, groq
    |
    */

    'provider' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | Configurações opcionais futuras
    |--------------------------------------------------------------------------
    |
    | Aqui você pode evoluir depois para:
    | - fallback chain
    | - roteamento inteligente
    | - limites por usuário
    |
    */

    'fallback_order' => [
        'gemini',
        'openai',
        'groq',
    ],

];
<?php

return [
    'anthropic' => [
        'name' => 'Claude (Anthropic)',
        'models' => [
            'claude-haiku-4-5-20251001' => 'Claude Haiku 4.5',
            'claude-opus-4-7' => 'Claude Opus 4.7',
            'claude-sonnet-4-7' => 'Claude Sonnet 4.7',
        ],
    ],
    'openai' => [
        'name' => 'GPT (OpenAI)',
        'models' => [
            'gpt-4o' => 'GPT-4o',
            'gpt-4o-mini' => 'GPT-4o Mini',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
        ],
    ],
    'gemini' => [
        'name' => 'Gemini (Google)',
        'models' => [
            'gemini-2.5-pro' => 'Gemini 2.5 Pro',
            'gemini-2.5-flash' => 'Gemini 2.5 Flash',
            'gemini-2.0-flash' => 'Gemini 2.0 Flash',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
        ],
    ],
];

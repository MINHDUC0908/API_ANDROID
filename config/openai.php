<?php

return [
    'api_key' => env('OPENAI_API_KEY'), // API key từ .env
    'base_uri' => env('OPENAI_BASE_URI', 'https://api.openai.com'), // Địa chỉ cơ sở của OpenAI API
];

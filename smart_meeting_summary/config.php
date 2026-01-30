<?php

// Load environment variables from .env file
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
 
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) {
            continue;
        }
 
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
}
 
loadEnv(__DIR__ . '/../.env');
 
return [
    'groq' => [
        'api_key' => $_ENV['GROQ_API_KEY'] ?? 'your-groq-api-key',
        'model' => 'llama-3.1-8b-instant',
        'max_tokens' => 2000,
        'temperature' => 0.3
    ],
 
    'app' => [
        'name' => 'Smart Meeting Summary',
        'version' => '1.0.0'
    ]
];

